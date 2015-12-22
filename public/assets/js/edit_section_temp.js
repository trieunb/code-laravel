
  var section = ['div[lang=name]', 'div[lang=skill]', 'div[lang=address]', 'div[lang=phone]',
      'div[lang=email]', 'div[lang=profile_website]', 'div[lang=linkedin]',
      'div[lang=reference]', 'div[lang=objective]', 'div[lang=activitie]',
      'div[lang=work]', 'div[lang=education]', 'div[lang=photo]', 'div[lang=personal_test]',
      'div[lang=key_qualification]',  'div[lang=infomation]'
  ];
  for(var i=0; i<section.length; i++)
  {
    var cls = section[i];
    var $sls = $(cls);
    if (section[i] != 'div[lang=photo]')
      $(cls).attr("contentEditable", true);
  }
  function clickEditTemplate() {
      var isBusy = false;

      if (isBusy) return;

      isBusy = true;
      $("#loading").show();
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
            $("#loading").hide();
            isBusy = false;
          }
        }
      }).always(function() {
          isBusy = false;
      });
  }
