<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>0. Design</title>
    <link
      type="text/css"
      href="https://uicdn.toast.com/tui-color-picker/v2.2.6/tui-color-picker.css"
      rel="stylesheet"
    />
    <link type="text/css" href="css/tui-image-editor.css" rel="stylesheet" />
    <style>
      @import url(http://fonts.googleapis.com/css?family=Noto+Sans);
      html,
      body {
        height: 100%;
        margin: 0;
      }
    </style>
  </head>
  <body>
    <div id="tui-image-editor-container"></div>
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.4.0/fabric.js"
    ></script>
    <script
      type="text/javascript"
      src="https://uicdn.toast.com/tui.code-snippet/v1.5.0/tui-code-snippet.min.js"
    ></script>
    <script
      type="text/javascript"
      src="https://uicdn.toast.com/tui-color-picker/v2.2.6/tui-color-picker.js"
    ></script>
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"
    ></script>
    <script type="text/javascript" src="js/tui-image-editor.js"></script>
    <script type="text/javascript" src="js/theme/white-theme.js"></script>
    <script type="text/javascript" src="js/theme/black-theme.js"></script>
    <script>
      // Image editor
      var imageEditor = new tui.ImageEditor('#tui-image-editor-container', {
        includeUI: {
          loadImage: {
            path: 'images/test.jpg',
            name: 'SampleImage',
          },
          theme: blackTheme, // or whiteTheme
          initMenu: 'filter',
          menuBarPosition: 'left',
        },
        cssMaxWidth: 1000,
        cssMaxHeight: 1000,
        usageStatistics: false,
      });
      window.onresize = function () {
        imageEditor.ui.resizeEditor();
      };
    </script>
  </body>
</html>
