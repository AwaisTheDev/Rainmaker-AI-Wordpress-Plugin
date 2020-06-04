jQuery(document).ready(function () {
  var the_terms = jQuery(".validateit");

  the_terms.click(function () {
    if (jQuery(this).is(":checked")) {
      jQuery("#submitBtn").removeAttr("disabled");
    }
  });
});
