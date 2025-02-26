<!DOCTYPE html>
<html>
<head>
    <title>DWG Viewer</title>
    <script src="https://developer.api.autodesk.com/modelderivative/v2/viewers/7.*/viewer3D.min.js"></script>
    <style>
        #forgeViewer {
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="forgeViewer"></div>
    <script>
        var viewer;

        function initializeViewer() {
            var options = {
                env: 'AutodeskProduction',
                getAccessToken: function(onGetAccessToken) {
                    fetch('/cadtoken').then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    }).then(data => {
                        console.log('Token data:', data);
                        onGetAccessToken(data.access_token, data.expires_in);
                    }).catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
                }
            };

            Autodesk.Viewing.Initializer(options, function() {
                var viewerDiv = document.getElementById('forgeViewer');
                viewer = new Autodesk.Viewing.GuiViewer3D(viewerDiv);
                viewer.start();

                var documentId = 'urn:dXJuOmFkc2sub2JqZWN0czpvcy5vYmplY3Q6Y2xldmVidWlsZC0wNS0yMS0yMDI0L3BocEFEMDEudG1w';
                console.log('Loading document:', documentId);
                Autodesk.Viewing.Document.load(documentId, onDocumentLoadSuccess, onDocumentLoadFailure);
            });
        }

        function onDocumentLoadSuccess(doc) {
            console.log('Document loaded successfully');
            var viewables = doc.getRoot().getDefaultGeometry();
            viewer.loadDocumentNode(doc, viewables).then(i => {
                console.log('Viewable geometry loaded successfully');
            });
        }

        function onDocumentLoadFailure(viewerErrorCode) {
            console.error('onDocumentLoadFailure() - errorCode:', viewerErrorCode);
        }

        initializeViewer();
    </script>
</body>
</html>
