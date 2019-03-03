<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>API</title>
    <link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
    <link href='/swagger/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/swagger/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/swagger/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/swagger/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='/swagger/css/print.css' media='print' rel='stylesheet' type='text/css'/>
    <script src='/swagger/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='/swagger/lib/js-yaml.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/lodash.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/backbone-min.js' type='text/javascript'></script>
    <script src='/swagger/swagger-ui.js' type='text/javascript'></script>
    <script src='/swagger/lib/highlight.9.1.0.pack.js' type='text/javascript'></script>
    <script src='/swagger/lib/highlight.9.1.0.pack_extended.js' type='text/javascript'></script>
    <script src='/swagger/lib/jsoneditor.min.js' type='text/javascript'></script>
    <script src='/swagger/lib/marked.js' type='text/javascript'></script>
    <script src='/swagger/lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- Some basic translations -->
    <!-- <script src='/swagger/lang/translator.js' type='text/javascript'></script> -->
    <!-- <script src='/swagger/lang/ru.js' type='text/javascript'></script> -->
    <!-- <script src='/swagger/lang/en.js' type='text/javascript'></script> -->

    <script type="text/javascript">
        $(function () {

            window.oAuthRedirectUrl = 'http://<?php echo e(env('API_DOMAIN')); ?>/oauth/access_token';
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "http://<?php echo e(env('API_DOMAIN')); ?>/swagger/config";
            }

            hljs.configure({
                highlightSizeThreshold: 5000
            });

            // Pre load translate...
            if(window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                    if(typeof initOAuth == "function") {
                        initOAuth({
                            clientId: "<?php echo e(env('OAUTH_CLIENT_ID')); ?>",
                            clientSecret: "<?php echo e(env('OAUTH_CLIENT_SECRET')); ?>",
                            realm: "your-realms",
                            appName: "io84-api",
                            scopeSeparator: ",",
                            additionalQueryStringParams: {}
                        });
                    }

                    if(window.SwaggerTranslator) {
                        window.SwaggerTranslator.translate();
                    }
                },
                onFailure: function(data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: false,
                defaultModelRendering: 'schema',
                showRequestHeaders: false
            });

            window.swaggerUi.load();


        });

        function log() {
            if ('console' in window) {
                console.log.apply(console, arguments);
            }
        }
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="/api"><img class="logo__img" alt="swagger" height="30" width="30" src="/swagger/images/logo_small.png" /><span class="logo__title">io84 API</span></a>
        <form id='api_selector'>
            <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
            <div id='auth_container'></div>
            <div class='input'><a id="explore" class="header__btn" href="#" data-sw-translate>Explore</a></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
