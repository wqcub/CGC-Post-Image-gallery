jQuery(function(i){i("#pig_image_desc").focus(function(){"Describe your image"==i(this).val()&&i(this).val("")}),i("#pig_submit").click(function(){i(this).attr("disabled","disabled"),i("#pig_submission").submit()}),i.validator.addMethod("notEqual",function(i,e,a){return this.optional(e)||i!=a}),i("#pig_image_desc").focus(function(){"Image Description"==i(this).val()&&i(this).val("")}),i(".pig_checkbox_wrapper a").click(function(e){e.preventDefault(),i(this).hasClass("okay")?(i(this).removeClass("okay"),i(this).parent().find("input").val(0)):(i(this).addClass("okay"),i(this).parent().find("input").val(1))}),i("#pig_gallery_submission").validate({ignore:".ignore",debug:!0,success:function(i){i.addClass("valid")},rules:{pig_image_name:{required:!0,maxlength:55},pig_image_desc:{required:!0,minlength:15,maxlength:1e3,notEqual:"Image Description"},pig_image_file:{required:!0},pig_agreement:{required:!0,notEqual:0}},submitHandler:function(e){i("#pig_submit").attr("disabled","disabled"),e.submit()}}),i('select[name="pig_3d_embed_type"]').change(function(){var e=i(this).val(),a=i('input[name="pig_3d_url"]');e?a.show():a.hide()}).change()});