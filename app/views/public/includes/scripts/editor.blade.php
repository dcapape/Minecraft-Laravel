$(document).ready(function() {
  var wbbOpt = {
    autoresize: true,
    resize_maxheight: 800,
    startHeight: {{$height}},
    buttons: 'bold,italic,underline,strike,|,img,video,link,|,bullist,numlist,|,fontcolor,fontsize,|,justifyleft,justifycenter,justifyright,|,quote,code,removeFormat',
    allButtons: {
      quote: {
        transform: {
          '<div class="quote">{SELTEXT}</div>':'[quote]{SELTEXT}[/quote]',
          '<div class="quote"><cite>{AUTHOR}:</cite><br>{SELTEXT}</div>':'[quote={AUTHOR}]{SELTEXT}[/quote]'
        }
      },
      fontsize: {
           type: 'select',
           title: CURLANG.fontsize,
           options: "fs_big,fs_normal,fs_small,fs_verysmall"
     },
     numlist: {
       title: CURLANG.numlist,
       buttonHTML: '<span class="fonticon ve-tlb-numlist1">\uE00a</span>',
       excmd: 'insertOrderedList',
       transform : {
         '<ol>{SELTEXT}</ol>':"[list=1]{SELTEXT}[/list]",
         '<li>{SELTEXT}</li>':"[*]{SELTEXT}"
       }
     },
     bullist : {
				title: CURLANG.bullist,
				buttonHTML: '<span class="fonticon ve-tlb-list1">\uE009</span>',
				excmd: 'insertUnorderedList',
				transform : {
					'<ul>{SELTEXT}</ul>':"[list]{SELTEXT}[/list]",
					'<li>{SELTEXT}</li>':"[*]{SELTEXT}"
				}
			},
      video: {
					title: CURLANG.video,
					buttonHTML: '<span class="fonticon ve-tlb-video1">\uE008</span>',
					modal: {
						title: CURLANG.video,
						width: "600px",
						tabs: [
							{
								title: CURLANG.video,
								input: [
									{param: "SRC",title:CURLANG.modal_video_text}
								]
							}
						],
						onSubmit: function(cmd,opt,queryState) {
							var url = this.$modal.find('input[name="SRC"]').val();
							if (url) {
								url = url.replace(/^\s+/,"").replace(/\s+$/,"");
							}
							var a;
							if (url.indexOf("youtu.be")!=-1) {
								a = url.match(/^http[s]*:\/\/youtu\.be\/([a-z0-9_-]+)/i);
							}else{
								a = url.match(/^http[s]*:\/\/www\.youtube\.com\/watch\?.*?v=([a-z0-9_-]+)/i);
							}
							if (a && a.length==2) {
								var code = a[1];
								this.insertAtCursor(this.getCodeByCommand(cmd,{src:code}));
							}
							this.closeModal();
							this.updateUI();
							return false;
						}
					},
					transform: {
						'<iframe src="https://www.youtube.com/embed/{SRC}" width="480" height="320" frameborder="0"></iframe>':'[video]{SRC}[/video]'
					}
				},
    }
  }
  $("#editor").wysibb(wbbOpt);

  $(".quotebtn").click(function() {
    var author = $(this).data('author');
    var quote = $(this).data('quote');
    $('#editor').execCommand('quote',{author: author,seltext:quote});
    $("#editor").sync();
  });

})
