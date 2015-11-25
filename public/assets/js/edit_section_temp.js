$(document).ready(function() {
  var section = ['div.name', 'div.address', 'div.phone',
      'div.email', 'div.profile_website', 'div.linkedin',
      'div.reference', 'div.objective', 'div.activitie',
      'div.work', 'div.education', 'div.photo', 'div.personal_test',
      'div.key_quanlification', 'div.availability', 'div.infomation'
  ];
  for(var i=0; i<section.length; i++)
  {
    var cls = section[i];
    var $sls = $(cls);
    $(cls).attr("contentEditable", true);
  }
  $('#edit-template').click(function(e) {
      e.preventDefault();
      var url = window.location.href;
      var token = url.split('=');
      var content = $('#content').html();
      content = content.replace(/\t|\n+/g, '');
      $.ajax({
        url: window.location.href,
        data: {
          token : token,
          content: content
        },
        type: 'POST',
        success : function(result) {
          if (result.status == true) {
            alert('Edit template successfully');
          }
        }
      });
    });
});