
<html>
	<title>PitchPrint Sample SDK</title>
	<head>
		
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//dta8vnpq1ae34.cloudfront.net/v8/js/pprint.js"></script>

		<script type="text/javascript">

			var rscCdn = '//dta8vnpq1ae34.cloudfront.net/';
			var rscBase = '//s3.amazonaws.com/pitchprint.rsc/';
			var langCode = 'en';
			var apiKey = 'f80b84b4eb5cc81a140cb90f52e824f6';
			var appUrl = 'https://pitchprint.net/app/';
			var designId = 'ab73e4cc5b80b05facfca35cca40d318';
			var lang, designer, mode = 'new', userId = 'guest', projectSource, previews, numPages, projectId, designerShown;


			function loadLanguage () {
				$.ajax( {
					type: 'GET',
					dataType: 'json',
					url: rscCdn + 'lang/' + (langCode || 'en'),
					cache: true,
					success: function(_r) {
						lang = _r;
						initEditor();
					}
				} );
			}

			function initEditor() {
				designer = new W2P.designer({
					apiKey: apiKey,
					globalUrlPrefix: appUrl,
					parentDiv: '#pp_inline_div',
					mode: mode,
					lang: lang,
					userId: userId,
					product: { title: 'Sample Card', id: '' },
					designId: designId,
					rscBase: rscBase,
					autoInitialize: true,
					isUserproject: false,
					isAdmin: false,
					langCode: langCode,
					
					onReady: onReady,
					autoShow: false,
					onSave: onSave,
					onShown: onShown
				});
			}
			
			function onShown() {
				designerShown = true;
			}
			
			function onReady() {
				$('#launch_btn').show();
				$('#pp_loader_div').hide();
			}
			
			function onSave(_val) {
				mode = 'edit';
				projectSource = _val.source;
				projectId = _val.projectId;
				numPages = _val.numPages;
				previews = _val.previews;
				
				//Let's show previews
				var _prevDiv = $('#pp_preview_div');
				_prevDiv.empty();
				
				for (var i=0; i < numPages; i++) {
					_prevDiv.append('<img style="border: 1px solid #eee; margin: 10px" src="' + rscBase + 'images/previews/' + projectId + '_' + (i+1) + '.jpg" >');		//Please note, previews are stored based on design page number, thus (i+1)... page 1, page 2...
				}
				
				designer.close(true);	//Setting this to true ensures the designer is active but with previews shown.
				
				//If you pass in false, you will need to collapse the editor or remove it from DOM.
				
				//Now show the launch button again, change the text to edit
				$('#launch_btn').show().text('Edit Design');
			}
			
			function showDesigner() {
				/*Here, we check if the designer has not been shown before, then we animage the container div's height, which was initially 0
					If designer has been shown before, we just call up the resume function on the designer..
				*/
				if (!designerShown) {
					TweenLite.to($('#pp_inline_div'), 0.6, { 'height': ($(window).height() - 150), ease: Power2.easeOut, onComplete: function() {
						designer.show();
						$('#launch_btn').hide();
					} } );
				} else {
					designer.resume();
				}
			}
			
			$(function() {
				$('#launch_btn').hide();
				loadLanguage();
			})
			
			

		</script>
	</head>
	<body>
		<div id="pp_inline_div" style="width:1200px; height: 0; margin: 0 auto"></div>
		<div id="pp_loader_div"><img src="//dta8vnpq1ae34.cloudfront.net/images/loaders/anim-ring.svg" ></div>
		<button id="launch_btn" onclick="showDesigner();" >Launch Designer</button>
		<div id="pp_preview_div"> </div>
	</body>
</html>