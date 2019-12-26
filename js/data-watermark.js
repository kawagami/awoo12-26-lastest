 $(document).ready(function () {
               $(":input[data-watermark]").each(function () {
                   $(this).val($(this).attr("data-watermark"));
                   $(this).bind('focus', function () {
                       if ($(this).val() == $(this).attr("data-watermark")) $(this).val('');
                   });
                   $(this).bind('blur', function () {
                       if ($(this).val() == '') $(this).val($(this).attr("data-watermark"));
                   });
               });
           });