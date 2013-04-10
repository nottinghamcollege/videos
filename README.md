# Dukt Videos for Craft

*Notice : Thank you for taking the time to test Dukt Videos. Please do not install on production environment, the plugin is not ready for this yet.* 

## Installation

1. Move `duktvideos/` folder to `/craft/plugins/`
2. In the admin, go to the **CP / Settings / Plugins** section and enable Dukt Videos plugin
3. In the admin, go to the **CP / Dukt Videos** section in order to configure YouTube & Vimeo

## Video Service Settings

Once the plugin is installed, the first thing you will want to do is configure a video service that you want to use. We currently support Vimeo and YouTube.

For each video service, you need to get access to an API and you will need credentials such as client id, secret or developer key. Here are the steps that you need to accomplish in order to get the required parameters.

#### Vimeo

*Required credentials : client id & secret*

1. [Create a new app](https://developer.vimeo.com/apps) in Vimeo Developer
2. Copy paste client_id and client secret_values to **CP / Dukt Videos / Vimeo Configuration**

#### YouTube 

*Required credentials : client id, secret, developer key*

1. [Create a new project](https://code.google.com/apis/console/) in Google APIs
2. Go to **Google APIs / Your Project / API Access** and click **Create an OAuth 2.0 client ID**
3. Give a product name and click **Next**
4. Your client should have the following settings :
	- Application type : Web Application
	- Authorized Redirect URIs (click more options) : **http://yourwebsite.com/index.php/admin/actions/duktvideos/settings/callback/youtube**
	- Authorized Javascript Origins : **http://yourwebsite.com/**
5. Copy paste client id and client secret values to **CP / Dukt Videos / YouTube Configuration**
6. [Register a new Developer Key](https://code.google.com/apis/youtube/dashboard)
7. Copy paste Developer Key to **CP / Dukt Videos / YouTube Configuration**

## Field Type

Dukt Videos field type lets you add videos to your entries. Retrieving video informations is then pretty easy : a video variable is provided which let's you retrieve all the informations related to your video.

	{% set embed_params = { width: 300, height: 200 } %}
	
	{% set video = entry.video %}
	
	<ul>
		<li>title : {{ video.title }}</li>
		<li>url : {{ video.url }}</li>
		<li>embed : {{ video.embed(embed_params) }}</li>
	</ul>


## craft.duktvideos.url(videoUrl)

Retrieve a video from its URL.

#### Parameters

- videoUrl : Url of the Vimeo or YouTube video

#### Return

- video

#### Example

	{% set video = craft.duktvideos.url('http://youtu.be/14pRmb5LAhU') %}

	{{video.embed({ width: 300, height: 200, autoplay: 1 })}}

	<ul>
		<li>title : {{ video.title }}</li>
		<li>description : {{ video.description }}</li>
		<li>url : {{ video.url }}</li>
	</ul>


