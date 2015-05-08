<?php

/**
 * Videos plugin for Craft CMS
 *
 * @package   Videos
 * @author    Benjamin David
 * @copyright Copyright (c) 2015, Dukt
 * @link      https://dukt.net/craft/videos/
 * @license   https://dukt.net/craft/videos/docs/license
 */

namespace Craft;

/**
 * Videos controller
 */
class VideosController extends BaseController
{
    /**
     * Connect
     */
    public function actionConnect()
    {
        // referer

        $referer = craft()->httpSession->get('videos.referer');

        if(!$referer)
        {
            $referer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('videos.referer', $referer);
        }


        // connect

        $gatewayHandle = craft()->request->getParam('gateway');
        $gateway = craft()->videos->getGatewayOpts($gatewayHandle);

        $scopes = craft()->videos->getScopes($gateway['oauth']['handle']);
        $params = craft()->videos->getParams($gateway['oauth']['handle']);

        $providerHandle = $gateway['oauth']['handle'];

        if($response = craft()->oauth->connect(array(
            'plugin' => 'videos',
            'provider' => $providerHandle,
            'scopes' => $scopes,
            'params' => $params
        )))
        {
            if($response['success'])
            {
                // token
                $token = $response['token'];

                // save token
                craft()->videos->saveToken($providerHandle, $token);

                // session notice
                craft()->userSession->setNotice(Craft::t("Connected."));
            }
            else
            {
                // session notice
                craft()->userSession->setError(Craft::t($response['errorMsg']));
            }
        }
        else
        {
            // session error
            craft()->userSession->setError(Craft::t("Couldn’t connect"));
        }


        // redirect

        craft()->httpSession->remove('videos.referer');

        $this->redirect($referer);
    }

    /**
     * Disconnect
     */
    public function actionDisconnect()
    {
        // handle
        $gateway = craft()->request->getParam('gateway');
        $gateway = craft()->videos->getGatewayOpts($gateway);
        $handle = $gateway['oauth']['handle'];


        // delete token
        craft()->videos->deleteToken($handle);

        // set notice
        craft()->userSession->setNotice(Craft::t("Disconnected."));

        // redirect
        $redirect = craft()->request->getUrlReferrer();
        $this->redirect($redirect);
    }

    /**
     * Returns a video by its URL.
     */
    public function actionLookupVideo()
    {
        $this->requireAjaxRequest();

        $url = craft()->request->getParam('url');

        try
        {
            $video = craft()->videos->getVideoByUrl($url);

            if(!$video)
            {
                throw new Exception("Video not found");

            }
            $this->returnJson(
                array(
                    'video' => $video,
                    'preview' => craft()->templates->render('videos/field/preview', array('video' => $video))
                )
            );
        }
        catch(\Exception $e)
        {
            $this->returnErrorJson($e->getMessage());
        }
    }

    /**
     * Get Videos from ko URL
     */

        // {gatewayHandle}/{method}/{id}

        // youtube/favorites
        // - gatewayHandle : youtube
        // - method : favorites
        // - params : {page:2}

        // youtube/uploaded
        // - gatewayHandle : youtube
        // - method : favorites
        // - params : {page:2}


        // youtube/search
        // - gatewayHandle : youtube
        // - method : search
        // - params : {q:"peter doherty"}

        // youtube/playlist/1
        // - gatewayHandle : youtube
        // - method : playlist
        // - params : {id:"1234"}

        // vimeo/album/1
        // - gatewayHandle : youtube
        // - method : playlist
        // - params : {id:"1234"}

    public function actionGetVideosFromUrl()
    {
        try
        {
            $gatewayHandle = false;
            $method = false;
            $id = false;

            // explode url

            $url = craft()->request->getParam('url');
            $url = trim($url, "/");
            $url = explode("/", $url);

            if(!empty($url[0]))
            {
                $gatewayHandle = $url[0];
            }

            if(!empty($url[1]))
            {
                $method = $url[1];
            }

            if(!empty($url[2]))
            {
                $id = $url[2];
            }

            // perform request

            $gateway = craft()->videos->getGateway($gatewayHandle);
            $response = array();

            if(!empty($gateway))
            {
                $realMethod = 'getVideos'.ucwords($method);
                $options = craft()->request->getParam('options');

                if($id)
                {
                    $options['id'] = $id;
                }

                $response = $gateway->{$realMethod}($options);
            }

            $this->returnJson($response);
        }
        catch(\Exception $e)
        {
            $this->returnErrorJson($e->getMessage());
        }
    }

    /**
     * Get gateways with sections
     */
    public function actionGetGatewaysWithSections()
    {
        try
        {
            $gateways = craft()->videos->getGatewaysWithSections();

            $this->returnJson(array(
                'gateways' => $gateways
            ));
        }
        catch(\Exception $e)
        {
            $this->returnErrorJson("Couldn't get gateways: ".$e->getMessage());
        }
    }

    /**
     * Manager modal
     */
    public function actionManager()
    {
        $this->renderTemplate('videos/modals/manager');
    }

    /**
     * Player Modal
     */
    public function actionPlayer()
    {
        $this->renderTemplate('videos/modals/player');
    }
}