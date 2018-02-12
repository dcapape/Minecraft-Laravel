@extends('public.layouts.default')

@section('title', ' - Shop')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      @if (@$item)
        <h3 class="panel-title">{{$item->name}}</h3>
      @else
        <h3 class="panel-title">Create new item</h3>
      @endif
    </div>

    <div class="panel-body">
      <div class="col-md-8">
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        @if ($errors->all())
            <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
        @endif

        @if (isset($item))
            {{ Form::model($item, array('route' => array('shop.item.update', $item->id), 'method' => 'PUT', 'files' => true)) }}
        @else
            {{ Form::open(array('route' => array('shop.item.store'), 'files' => true)) }}
        @endif

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('categoryId', 'Category') }}
            {{ Form::select('categoryId', $categories, Input::old('categoryId'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('slug', 'Slug') }}
            {{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('image', 'Image') }}
            {{ Form::file('image', array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('command', 'Command') }}
            {{ Form::textarea('command', Input::old('command'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('allopassId', 'Allopass Product ID') }}
            {{ Form::text('allopassId', Input::old('allopassId'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('weight', 'Weight') }}
            {{ Form::text('weight', Input::old('weight'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('sellable', 'Sellable') }}
            {{ Form::checkbox('sellable', true, Input::old('sellable'), array('class' => 'form-control')) }}
        </div>

        {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

        @if (isset($item))
          {{ Form::open(array('route' => array('shop.item.destroy', $item->id), 'class' => 'pull-right')) }}
              {{ Form::hidden('_method', 'DELETE') }}
              {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
          {{ Form::close() }}
        @endif
      </div>
      <div class="col-md-4">
          @if (isset($item))
            @foreach($item->costs as $cost)

              <p><a href="/shop/item/{{$item->id}}/cost/{{$cost->id}}/edit">
                @if ($cost->serverId)
                  {{@Server::find($cost->serverId)->name;}}
                @else
                  Global
                @endif
                  {{$cost->price}} {{$cost->coin}}</a><p>
            @endforeach

            <p><a href="/shop/item/{{$item->id}}/cost/create">Add cost</a><p>
          @endif
      </div>
    </div>
  </div>



  <div class="modal fade" id="itemCreatorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">Item Creator</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" placeholder="Player (ex Notch, @a, @p)" id="player" value="@p" class="form-control">
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">minecraft:</span>
      					<input type="text" id="item" class="form-control" placeholder="stone">
      				</div>
      			</div>
            <div class="col-md-5 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon"><input type="checkbox" id="useItemName"> Name <span id="repairCostQuestion" data-placement="top" title="Help" data-content="This will replace the default name. If this matches the Lock NBT tag of a container, it can open that container." class="glyphicon glyphicon-question-sign"></span></span>
      					<input id="itmName" type="text" value="Check the checkbox to use a custom name" disabled class="form-control">
      				</div>
      			</div>
      			<div class="col-md-3 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">Quantity</span>
      					<input id="qty" type="text" value="1" class="form-control">
      				</div>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">Repair Cost <span id="repairCostQuestion" data-placement="top" title="Help" data-content="This value will be added to the standard repair cost." class="glyphicon glyphicon-question-sign"></span></span>
      					<input id="repairCost" type="text" value="0" class="form-control">
      				</div>
      			</div>
            <div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">Damage <span id="repairCostQuestion" data-placement="top" title="Help" data-content="This is used to determine tool durability, and for things such as water height." class="glyphicon glyphicon-question-sign"></span></span>
      					<input id="dmg" type="text" value="0" class="form-control">
      				</div>
      			</div>
      			<div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">
      						<input id="invul" type="checkbox">
      					</span>
      					<div type="text" class="form-control">Unbreakable <span id="repairCostQuestion" data-placement="top" title="Help" data-content="This will make it so the item never takes damage." class="glyphicon glyphicon-question-sign"></span></div>
      				</div>
      			</div>
      		</div>
      		<hr>
      		<div class="row flags">
      			<div class="col-md-2 col-flags">
      				<h4>Flags</h4>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="HFEnchantments" class="hfToggle btn btn-block btn-success">Enchantments</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="HFModifiers" class="hfToggle btn btn-block btn-success">Modifiers</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="HFUnbreakable" class="hfToggle btn btn-block btn-success">Unbreakable</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="HFCanDestroy" class="hfToggle btn btn-block btn-success">CanDestroy</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="HFCanPlace" class="hfToggle btn btn-block btn-success">CanPlace</button>
      			</div>
      		</div>
      		<hr>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-2 col-flags">
      				<h4>Lore Text</h4>
      			</div>
      			<div class="col-md-7 col-flags">
      				<input id="loreAdd" class="form-control">
      			</div>
      			<div class="col-md-3 col-flags">
      				<button id="loreAddBtn" class="btn btn-block btn-success">Add Lore</button>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<h4>Can Destroy</h4>
      			</div>
      			<div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">minecraft:</span>
      					<input type="text" id="canDestroy" class="form-control" placeholder="stone">
      				</div>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="destroyAdd" class="btn btn-block btn-success col-flags">
      					Add
      				</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="destroyRemove" class="btn btn-block btn-danger col-flags">
      					Remove
      				</button>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<h4>Can Place On</h4>
      			</div>
      			<div class="col-md-4 col-flags">
      				<div class="input-group">
      					<span class="input-group-addon">minecraft:</span>
      					<input type="text" id="canPlaceOn" class="form-control col-flags" placeholder="stone">
      				</div>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="placeOnAdd" class="btn btn-block btn-success col-flags">
      					Add
      				</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="placeOnRemove" class="btn btn-block btn-danger col-flags">
      					Remove
      				</button>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<h4>Enchantment</h4>
      			</div>
      			<div class="col-md-2 col-flags">
      				<select id="enchID" class="form-control">
      					<option value="0">Protection</option>
      					<option value="1">Fire Protection</option>
      					<option value="2">Feather Falling</option>
      					<option value="3">Blast Protection</option>
      					<option value="4">Projectile Protection</option>
      					<option value="5">Respiration</option>
      					<option value="6">Aqua Affinity</option>
      					<option value="7">Thorns</option>
      					<option value="16">Sharpness</option>
      					<option value="17">Smite</option>
      					<option value="18">Bane of Athropods</option>
      					<option value="19">Knockback</option>
      					<option value="20">Fire Aspect</option>
      					<option value="21">Looting</option>
      					<option value="32">Efficiency</option>
      					<option value="33">Silk Touch</option>
      					<option value="34">Unbreaking</option>
      					<option value="35">Fortune</option>
      					<option value="48">Power</option>
      					<option value="49">Punch</option>
      					<option value="50">Flame</option>
      					<option value="51">Infinity</option>
      					<option value="61">Luck of the Sea</option>
      					<option value="62">Lure</option>
      					<option value="70">Mending (?)</option>
      				</select>
      			</div>
      			<div class="col-md-2 col-flags">
      				<input placeholder="Level" id="enchQTY" class="form-control">
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="enchAdd" class="btn btn-block btn-success col-flags">
      					Add
      				</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="enchRemove" class="btn btn-block btn-danger col-flags">
      					Remove
      				</button>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-4 col-flags">
      				<h4>Stored Enchants</h4>
      			</div>
      			<div class="col-md-2 col-flags">
      				<select id="enchStoreID" class="form-control">
      					<option value="0">Protection</option>
      					<option value="1">Fire Protection</option>
      					<option value="2">Feather Falling</option>
      					<option value="3">Blast Protection</option>
      					<option value="4">Projectile Protection</option>
      					<option value="5">Respiration</option>
      					<option value="6">Aqua Affinity</option>
      					<option value="7">Thorns</option>
      					<option value="16">Sharpness</option>
      					<option value="17">Smite</option>
      					<option value="18">Bane of Athropods</option>
      					<option value="19">Knockback</option>
      					<option value="20">Fire Aspect</option>
      					<option value="21">Looting</option>
      					<option value="32">Efficiency</option>
      					<option value="33">Silk Touch</option>
      					<option value="34">Unbreaking</option>
      					<option value="35">Fortune</option>
      					<option value="48">Power</option>
      					<option value="49">Punch</option>
      					<option value="50">Flame</option>
      					<option value="51">Infinity</option>
      					<option value="61">Luck of the Sea</option>
      					<option value="62">Lure</option>
      					<option value="70">Mending (?)</option>
      				</select>
      			</div>
      			<div class="col-md-2 col-flags">
      				<input placeholder="Level" id="enchStoreQTY" class="form-control">
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="enchAddStore" class="btn btn-block btn-success col-flags">
      					Add
      				</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="enchRemoveStore" class="btn btn-block btn-danger col-flags">
      					Remove
      				</button>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-2 col-flags">
      				<h4>Attribute</h4>
      			</div>
      			<div class="col-md-2 col-flags">
      				<select id="attributeName" class="form-control">
      					<option value="generic.maxHealth">Max Health</option>
      					<option value="generic.followRange">Follow Range</option>
      					<option value="generic.knockbackResistance">Knockback Resistance</option>
      					<option value="generic.movementSpeed">Movement Speed</option>
      					<option value="generic.attackDamage">Attack Damage</option>
      				</select>
      			</div>
      			<div class="col-md-2 col-flags">
      				<input type="text" placeholder="Amount" class="form-control" id="attributeAmount">
      			</div>
      			<div class="col-md-2 col-flags">
      				<select id="attributeOp" class="form-control">
      					<option value="0">+/- amount</option>
      					<option value="1">+/- amount % (additive)</option>
      					<option value="2">+/- amount % (multiplicative)</option>
      				</select>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="attrAdd" class="btn btn-block btn-success col-flags">
      					Add
      				</button>
      			</div>
      			<div class="col-md-2 col-flags">
      				<button id="attrRemove" class="btn btn-block btn-danger col-flags">
      					Remove
      				</button>
      			</div>
      		</div>
      		<hr>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-12 col-flags">
      				<div class="input-group" id="previewcolorcontainer">
      					<span class="input-group-addon">Armor Color <input id="previewcolorcheckbox" type="checkbox"></span>
      					<input id="previewcolor" type="text" value="F5774A" class="color form-control">
      				</div>
      			</div>
      		</div>
      		<div class="row-margin-top row-margin-bottom row">
      			<div class="col-md-12 col-flags">
      				<textarea id="out" class="form-control" style="margin-top:5px">

      				</textarea>
      			</div>
      		</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary generateItem" data-dismiss="modal">Accept</button>
        </div>
      </div>
    </div>
  </div>


@stop

@section('script')
  var skeleton = "";

$(document).ready(function() {
  var wbbOpt = {
    autoresize: true,
    resize_maxheight: 800,
    startHeight: 500,
    buttons: 'bold,italic,underline,strike,|,mcitem,img,video,link,|,bullist,numlist,|,fontcolor,fontsize,|,justifyleft,justifycenter,justifyright,|,quote,code,removeFormat',
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
      mcitem: {
        title: 'Insert Minecraft Item',
        buttonHTML: '<span class="fonticon ve-tlb-list1">MC</span>',
        transform: {
          '<span class=\'item item_{SELTEXT}\'>.</span>':'[mc]{SELTEXT}[/mc]'
        }
      }
    }
  }
  $("#description").wysibb(wbbOpt);

  $(".quotebtn").click(function() {
    var author = $(this).data('author');
    var quote = $(this).data('quote');
    $('#editor').execCommand('quote',{author: author,seltext:quote});
    $("#editor").sync();
  });


  $("#command").parent().append('\
        <div id="commandManager">\
          <div id="commandToolbar">\
            <div class="btn-group" role="group" aria-label="...">\
              <button type="button" class="btn btn-success btn-sm additem">\
                <span class="fa fa-cube" aria-hidden="true" id="additem"></span> Add item\
              </button>\
              <button type="button" class="btn btn-success btn-sm addrank">\
                <span class="fa fa-sitemap" aria-hidden="true" id="addrank"></span> Add rank\
              </button>\
              <button type="button" class="btn btn-success btn-sm addpermission">\
                <span class="fa fa-terminal" aria-hidden="true" id="addpermission"></span> Add permission\
              </button>\
            </div>\
            <button type="button" class="btn-json btn btn-default btn-sm pull-right">\
              <span class="fa fa-file-code-o" aria-hidden="true" id="addpermission"></span> JSON\
            </button>\
          </div>\
          <div id="commandTextarea">\
          </div>\
          <div id="commandGui" class="cont">\
            <div class="col-md-4 list left-col">\
              <div class="col-sm items"></div>\
              <div class="col-sm ranks"></div>\
              <div class="col-sm permissions"></div>\
            </div>\
            <div class="col-md-8 right-col">\
              <div class="col-sm details"></div>\
            </div>\
          </div>\
        </div>\
        ');

    commandTextarea.appendChild(command);
    $("#commandTextarea").hide();

    var transforms = {
        "itemsList":[
          {"<>":"div", "class":"col-sm items","html":[
            {"<>":"span","html":"Items"},
            {"<>":"ul","html":function(){
                return($.json2html(skeleton.items,transforms.items));
            }
        }]}],
        "ranksList":[
          {"<>":"div", "class":"col-sm ranks","html":[
            {"<>":"span","html":"Rank"},
            {"<>":"ul","html":function(){
              return($.json2html(skeleton.ranks,transforms.ranks));
            }
        }]}],
        "permissionsList":[
          {"<>":"div", "class":"col-sm permissions","html":[
            {"<>":"span","html":"Permissions"},
            {"<>":"ul","html":function(){
              return($.json2html(skeleton.permissions,transforms.permissions));
            }
        }]}],

        "items":{"<>":"li","html":function(obj,index){
                    return( "<i class='fa fa-times deleteElement' data-type='items' data-index="+index+"></i> " + obj.name);
                },"onclick":function(e){
            $("#commandGui .details").empty().json2html(e.obj,transforms.itemsDetails);
        }},

        "ranks":{"<>":"li","html":function(obj,index){
                    return( "<i class='fa fa-times deleteElement' data-type='ranks' data-index="+index+"></i> " + obj.name);
                },"onclick":function(e){
            $("#commandGui .details").empty().json2html(e.obj,transforms.ranksDetails);

        }},

        "permissions":{"<>":"li","html":function(obj,index){
                    return( "<i class='fa fa-times deleteElement' data-type='permissions' data-index="+index+"></i> " + obj.name);
                },"onclick":function(e){
            $("#commandGui .details").empty().json2html(e.obj,transforms.permissionsDetails);

        }},

        "itemsDetails":[
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandName" ,"html":"Name"},
            {"<>":"input", "id":"commandName", "class":"form-control", "type":"text","value":"${name}"},
          ]},
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandCommand" ,"html":"Item"},
            {"<>":"textarea", "id":"commandCommand", "class":"form-control","html":"${item}"},
            {"<>":"button", "type":"button", "class":"btn btn-success btn-sm", "data-toggle":"modal", "data-target":"#itemCreatorModal" ,"html":"Generator"},
          ]},
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandQuantity" ,"html":"Quantity"},
            {"<>":"input", "id":"commandQuantity", "class":"form-control", "type":"text","value":"${quantity}"},
          ]},
          {'<>':'button', "type":"button", "class":"btn btn-success btn-sm",'html':'Save','onclick':function(e){
              e.obj.name = $("#commandName").val();
              e.obj.item = $("#commandCommand").val();
              e.obj.quantity = $("#commandQuantity").val();

              $("#command").val(JSON.stringify(skeleton));
              $('#commandGui .items').json2html({},transforms.itemsList,{"replace":"true"});
          }},
        ],
        "ranksDetails":[
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandName" ,"html":"Name"},
            {"<>":"input", "id":"commandName", "class":"form-control", "type":"text","value":"${name}"},
          ]},
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandCommand" ,"html":"Rank"},
            {"<>":"input", "id":"commandCommand", "class":"form-control", "type":"text","value":"${rank}"},
          ]},
          {'<>':'button', "type":"button", "class":"btn btn-success btn-sm",'html':'Save','onclick':function(e){
              e.obj.name = $("#commandName").val();
              e.obj.rank = $("#commandCommand").val();

              $("#command").val(JSON.stringify(skeleton));
              $('#commandGui .ranks').json2html({},transforms.ranksList,{"replace":"true"});
          }},
        ],
        "permissionsDetails":[
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandName" ,"html":"Name"},
            {"<>":"input", "id":"commandName", "class":"form-control", "type":"text","value":"${name}"},
          ]},
          {"<>":"div", "class":"form-group","html":[
            {"<>":"label", "for":"commandCommand" ,"html":"Permission"},
            {"<>":"input", "id":"commandCommand", "class":"form-control", "type":"text","value":"${permission}"},
          ]},
          {'<>':'button', "type":"button", "class":"btn btn-success btn-sm",'html':'Save','onclick':function(e){
              e.obj.name = $("#commandName").val();
              e.obj.permission = $("#commandCommand").val();
              //console.log(e.obj);
              //console.log(skeleton);
              $("#command").val(JSON.stringify(skeleton));
              $('#commandGui .permissions').json2html({},transforms.permissionsList,{"replace":"true"});
          }},
        ]
    };

    function generateHTML(){
      try {
        skeleton = JSON.parse($("#command").val());
      } catch(e) {
        console.log($("#command").val());
        skeleton = JSON.parse("{}");
        $("#command").val(JSON.stringify(skeleton));
      }
      $('#commandGui .items').empty().json2html({},transforms.itemsList,{"replace":"true"});
      $('#commandGui .ranks').empty().json2html({},transforms.ranksList,{"replace":"true"});
      $('#commandGui .permissions').empty().json2html({},transforms.permissionsList,{"replace":"true"});
    }

generateHTML();

var jsoncode = false;
$('.btn-json').click(function(){
  if (jsoncode){
    generateHTML();
    jsoncode = false;
  }else{
    jsoncode = true;
  }
    $("#commandGui").toggle();
    $("#commandTextarea").toggle();
});

$('.additem').click(function(){
  if (!skeleton.items){
    skeleton.items = [];
  }
  var element = {};
  element.name = "New Item";
  element.item = "";
  element.quantity = "1";
  skeleton.items.push(element);
  $("#command").val(JSON.stringify(skeleton));
  generateHTML();
});

$('.addrank').click(function(){
  if (skeleton.ranks && skeleton.ranks.length > 0){
    alert("Only allowed to assing 1 rank");
  }else{
    var element = {};
    element.name = "New Rank";
    element.rank = "";
    skeleton.ranks = [];
    skeleton.ranks.push(element);
    $("#command").val(JSON.stringify(skeleton));
    generateHTML();
  }
});

$('.addpermission').click(function(){
  if (!skeleton.permissions){
    skeleton.permissions = [];
  }
  var element = {};
  element.name = "New Permission";
  element.permission = "";
  skeleton.permissions.push(element);
  $("#command").val(JSON.stringify(skeleton));
  generateHTML();
});

$( "#commandGui" ).on( "click", ".deleteElement", function() {
  var index = $(this).data('index');
  var type = $(this).data('type');

  if (type === "items"){
    skeleton.items.splice(index,1);
  }
  if (type === "ranks"){
    skeleton.ranks.splice(index,1);
  }
  if (type === "permissions"){
    skeleton.permissions.splice(index,1);
  }

  $("#command").val(JSON.stringify(skeleton));
  generateHTML();
});

$('.generateItem').click(function() {
  var command = $( "#out" ).val();
  command = command.replace(/\@/g, "");
  command = command.replace("/give p ", "");
  $('#commandCommand').val(command);
});

})

var obfuscators = [];
$('.mc-k').each(function(index, elem) {
  	var magicSpan, currNode;
    var string = elem.innerText;

    if(string.indexOf('<br>') > -1) {
        elem.innerHTML = string;
        for(var j = 0, len = elem.childNodes.length; j < len; j++) {
            currNode = elem.childNodes[j];
            if(currNode.nodeType === 3) {
                magicSpan = document.createElement('span');
                magicSpan.innerHTML = currNode.nodeValue;
                elem.replaceChild(magicSpan, currNode);
                init(magicSpan);
            }
        }
    } else {
        init(elem, string);
    }
    function init(el, str) {
        var i = 0,
            obsStr = str || el.innerHTML,
            len = obsStr.length;
        obfuscators.push( window.setInterval(function () {
            if(i >= len) i = 0;
            obsStr = replaceRand(obsStr, i);
            el.innerHTML = obsStr;
            i++;
            wrap_letters(el);
        }, 0) );
    }
    function randInt(min, max) {
        return Math.floor( Math.random() * (max - min + 1) ) + min;
    }
    function replaceRand(string, i) {
        var randChar = String.fromCharCode( randInt(63, 96) );
        return string.substr(0, i) + randChar + string.substr(i + 1, string.length);
    }
});



function wrap_letters($element) {
    for (var i = 0; i < $element.childNodes.length; i++) {
        var $child = $element.childNodes[i];

        if ($child.nodeType === Node.TEXT_NODE) {
            var $wrapper = document.createDocumentFragment();

            for (var i = 0; i < $child.nodeValue.length; i++) {
                var $char = document.createElement('span');
                $char.className = 'char';
                $char.textContent = $child.nodeValue.charAt(i);

                $wrapper.appendChild($char);
            }

            $element.replaceChild($wrapper, $child);
        } else if ($child.nodeType === Node.ELEMENT_NODE) {
            wrap_letters($child);
        }
    }
}

@stop
