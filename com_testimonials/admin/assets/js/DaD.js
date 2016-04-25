jQuery( document ).ready(function() {

    //Work area
    jQuery(".tst_drop_field").each(function() {
        setWatcher(jQuery(this));
    });

    jQuery(document).on('dragenter', function (e)
    {
        e.stopPropagation();
        e.preventDefault();
    });
    jQuery(document).on('drop', function (e)
    {
        e.stopPropagation();
        e.preventDefault();
    });

    //Check drags events
    function setWatcher(obj) {

        var singleArray = [];

        jQuery(document).on('dragover', function (e)
        {
            e.stopPropagation();
            e.preventDefault();
            obj.css('border', '2px dotted #0B85A1');
        });

        obj.on('dragenter', function (e)
        {
            e.stopPropagation();
            e.preventDefault();
            jQuery(obj).css('border', '2px solid #0B85A1');
        });
        obj.on('dragover', function (e)
        {
            e.stopPropagation();
            e.preventDefault();
        });
        obj.on('drop', function (e)
        {
            jQuery(obj).css('border', '2px dotted #0B85A1');
            e.preventDefault();
            var files = e.originalEvent.dataTransfer.files;

            handleFileUpload(files,obj);
        });
        jQuery(obj).find('input[type=file]').change(function() {
            var fd = new FormData();
            fd.append('file', this.files[0]);
            sendFileToServer(fd,status);
        });

        function handleFileUpload(files)
        {
            for (var i = 0; i < files.length; i++)
            {
                var fd = new FormData();
                fd.append('file', files[i]);
                sendFileToServer(fd,status);
            }
        }
        //Delete image
        function deleteImageFromArray (id) {
            delete singleArray[id];
            if (singleArray[id] == undefined) return true;
            else return false;
        }

        //Insert image to hidden input
        function insertImagesArrayToField() {
            jQuery(obj).find('.thumbsData').val();
            string = singleArray.join('|');
            out = jQuery(obj).find('.thumbsData').val(string);
        }

        //Check Element On Unique
        function CheckElementOnUnique(value) {
            array = singleArray;

            for (var i = 0; i < array.length; i++) {
                if (array[i] == value) return false;
            }

            return true;
        }

        //Send Files
        function sendFileToServer(formData)
        {
            var uploadURL = window.location.href; //Upload URL

            var jqXHR=jQuery.ajax({
                url: uploadURL,
                type: "POST",
                contentType:false,
                processData: false,
                cache: false,
                data: formData,
                success: function(data){

                    //Generic html box for image
                    var img_outer = jQuery('<div class="tst_img_outer">');
                    var img_close = jQuery('<div class="tst_img_close">');
                    var img = jQuery('<img class="tst_thumbs" data-image="'+(singleArray.length)+'">');

                    if (CheckElementOnUnique(data)) {
                        //Check uploads errors
                        if (data != '/administrator/components/com_testimonials/assets/images/not_found.gif') singleArray.push(data);

                        img.attr('src', data); // Insert ajax response path
                        img_close.appendTo(img_outer);
                        img.appendTo(img_outer);
                        img_outer.appendTo(jQuery(obj).next('.tst_image_container'));
                        //Add delete event for new close element
                        jQuery(img_close).click(function () {
                            id = jQuery(this).next().attr('data-image');
                            jQuery(this).parent().remove();
                            deleteImageFromArray(id);
                            insertImagesArrayToField();
                        });

                        insertImagesArrayToField();
                    }
                    else alert('Image already upload')
                }
            });
        }
    }

});
