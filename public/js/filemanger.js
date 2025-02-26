(function( $ ){

  $.fn.filemanager2 = function(type, options) {
    type = type || 'file';

    this.on('click', function(e) {
      var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
      var target_input = $('#' + $(this).data('input'));
      var target_preview = $('#' + $(this).data('preview'));
      window.open(route_prefix + '?type=' + type + '&multiple=false', 'FileManager', 'width=900,height=600');
      window.SetUrl = function (items) {
        var file_path = items.map(function (item) {
          return item.url;
        }).join(',');

        console.log("123");

        // // set the value of the desired input to image url
        target_input.val('').val(file_path);

        // // clear previous preview
        // target_preview.html('');

        // // set or change the preview image src
        items.forEach(function (item) {
        //   target_preview.append(
            target_preview.attr('src', item.thumb_url)
        //   );
        });

        // // trigger change event
        // target_preview.trigger('change');
      };
      return false;
    });
  }
  
  $.fn.gallery = function(type, options) {
    type = type || 'file';


 
    this.on('click', function(e) {
      var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
      var target_input = $('#' + $(this).data('input'));
      var target_preview = $('#' + $(this).data('preview'));
      
      window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
      window.SetUrl = function (items) {
        var file_path = items.map(function (item) {
          return item.url;
        }).join(',');

     
        // set the value of the desired input to image url
        target_input.val('').val(file_path).trigger('change');

        // clear previous preview
        // target_preview.html('');
       var a = target_preview.find('.image-preview').length;
       console.log('preview_length',a);
       //Dynamic name
       var fieldname;
       if (options && options.fieldname) {
        fieldname = options.fieldname + '[task_images]';
       } 
       else{
        console.log('else')
        fieldname = 'task_images';
       }

        // set or change the preview image src
        if(a >= 1){
          items.forEach(function (item, index ) {
            var b = index + parseInt(a);
            target_preview.append(
              `<div class="col-md-2 col-12 image-preview">
                <div class="image-container">
                  <img src="${item.thumb_url}" style="height:150px; width:150px">
                  <span class="remove-icon" onclick="$(this).parent('.image-preview').remove()">&times;</span>
                  <input type="hidden" name="`+fieldname+`[`+b+`]" id="gallery-`+b+`" value="${item.url}" />
                  <div class="view-icon" onclick="showImageInModal('${item.url}')"><i class="fas fa-search-plus"></i></div>
                </div>
              </div>`
            );
          });
        }else{
          items.forEach(function (item, index) {
            target_preview.append(
              `
              <div class="col-md-2 col-12 image-preview">
                <div class="image-container">
                  <img src="${item.thumb_url}" style="height:150px; width:150px">
                  <span class="remove-icon" onclick="$(this).parent('.image-preview').remove()">&times;</span>
                  <input type="hidden" name="`+fieldname+`[`+index+`]" id="gallery-`+index+`" value="${item.url}" />
                  <div class="view-icon" onclick="showImageInModal('${item.url}')"><i class="fas fa-search-plus"></i></div>
                </div>
              </div>
              `
            );
          });  
        }
        // trigger change event
        target_preview.trigger('change');
      };
      return false;
    });
  }
})(jQuery);