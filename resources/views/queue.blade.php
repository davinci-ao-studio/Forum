@extends('layouts.app')
@section('content')

<div class="container col-md-12">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">In Behandeling</div>
                  <div class="panel-body">
                    <table class="table table-hover">
                      <thead>
                        <th>Gemaakt op</th>
                        <th>Tags</th>
                        <th>title</th>
                        <th>naam</th>
                        <th>Actie</th>
                      </thead>
                      <tbody id="behandeling">
                    </tbody>
                    </table>
                  </div>
            </div>
        </div>
    </div>
    <div class="container col-md-12">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Wachtrij<span style="float:right; text-align:right;" id="ticket"></span></div>
                  <div class="panel-body">
                    <table class="table table-hover">
                      <thead>
                        <th>Gemaakt op</th>
                        <th>Tags</th>
                        <th>title</th>
                        <th>naam</th>
                        <th>Actie</th>
                      </thead>
                      <tbody id="open">
                    </tbody></table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog" title="Support ticket">
  <div class="panel panel-default">
    <div class="panel-body">
      <tbody>
        {!! Form::open(array('name' => 'Ticket', 'method' => 'POST'))!!}
          <input type="hidden" id="token" name="_token" value="{!! csrf_token() !!}">
            <div class="col-md-6">
              {!! Form::label('name', "tag 1")!!}
                <select id="tag1" name="tag1">
                  @foreach($tags as $tag)
                    @if($tag->active == 1)
                      <option name="objectid" value="{{$tag->id}}">{{$tag->tag_name}}</option>
                    @endif
                  @endforeach
                </select>
                </div>
                <div class="col-md-6">
                  {!! Form::label('name', "Tag 2")!!}
                  <br> 
                  <select id="tag2" name="tag2">
                        <option name="objectid" value="null"></option>
                    @foreach($tags as $tag)
                      @if($tag->active == 1)
                        <option name="objectid" value="{{$tag->id}}">{{$tag->tag_name}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                  Algemeen probleem <input type="text" id="title" name="title" class="form-control"><br>
                <button onclick="checkForm(); submitdata()" type="button" id="sendButton">Submit ticket</button>

            </div>
                {!! Form::close()!!}
              </tbody>
            </table>
          </div>
        </div>
      </tbody>
    </div>
  </div>
</div>

<script>
  var refInterval = window.setInterval('update()', 500);
  var actiefInterval = window.setInterval('actief()', 500);
  var update = function() {
    $.ajax({
      type : 'GET',
      url : '/queue/ajax',
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
      success : InBehandeling});
  };
  update();

  var actief = function() {
    $.ajax({
      type : 'GET',
      url : '/queue/actief',
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
      success : checker});
  };
  actief();

  function checker(data){
    var result = data[0];
    var ticket = document.getElementById("ticket");
    
    ticket.innerHTML = '<button id="opener2">Nieuw probleem</button>';
    $( "#opener2" ).click(handleOpenerClick);
    if (result.active === 1){
          ticket.innerHTML = '<button id="cancel" onclick="cancelticket(<?=$user->id?>)">Cancel</button>';
    }
     
  }

  function InBehandeling(data){
    var id = data.userid;
    var role = data.role;
    data = data.queues;
    <?php $check = false; ?>
    var loops = data.length;
    var open = document.getElementById("open");
    var behandelingen = document.getElementById("behandeling");
    
    behandelingen.innerHTML = "";
    open.innerHTML = "";
    //console.log(data[0].tag[0].tag_name);
      for (var i = 0; i < loops; i++){
        var total = -1
        var tags = "";

        total += data[i].tag.length;
        for (var t = 0; t <= total; t++){
          tags = tags+"<span class='label label-primary' style='background-color:#337ab7;'>" + data[i].tag[t].tag_name + "</span>  ";
        }
     if(role === 1 || id === data[i].user_id){ 
      if(data[i].status === 1){
        
      var   behandeling = '<td>'+ data[i].created_at+'</td>'
          +'<td>' + tags + '</td>'
          +'<td>' + data[i].title + '</td>'
          +'<td>' + data[i].user.name +  '</td>'
          +'<td>' + '<button class="btn btn-primary" onclick="statusupdate('+data[i].id+')">Afsluiten</button> </tr>'; 
          behandelingen.innerHTML += behandeling;
          }

         
         
       }

       if(data[i].status === 0){
      var   openingen = '<tr><td>' + data[i].created_at+'</td>'
          +'<td>' + tags + '</td>'
          +'<td>' + data[i].title + '</td>'
          +'<td>' + data[i].user.name +  '</td>';
        if(data[i].user_id === id){
       openingen = openingen + '<td> <button class="btn btn-primary" onclick="statusupdate('+data[i].id+')">Behandelen</button></td>';
        }

        openingen = openingen + '</tr>';
          open.innerHTML += openingen;
         }  
       }
   }

var handleOpenerClick = function(e) {
  $( "#dialog" ).dialog( "open" );

}

$(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 500
      },
      hide: {
        effect: "explode",
        duration: 500
      }
    });

    //$( "#opener" ).click(handleOpenerClick);
});

function submitdata()
{
var disabled=document.getElementById("sendButton")
var tag1=document.getElementById( "tag1" );
var tag2=document.getElementById( "tag2" );
var title=document.getElementById( "title" );

var token=document.getElementById( "token" );
$.ajax({
        type: 'post',
        url: '/queue',
        data: {
        tag1:tag1.value,
        tag2:tag2.value,
        title:title.value,
        _token:token.value
        },
        success: function (response) {
          $( "#dialog" ).dialog( "close" );
         var ticket = document.getElementById("ticket");
        ticket.innerHTML = '<button id="cancel" onclick="cancelticket(<?=$user->id?>)">Cancel</button>';
        disabled.disabled = false;
        }
    });
return false;

}

function cancelticket(id){
  $.ajax({
        type: 'get',
        url: '/queue/'+id+'/edit',
        data: {
        id:id,
        _token:token.value
        },
        success: function (response) {

        }
});
}

function statusupdate(data)
{
  var token=document.getElementById( "token" );
  $.ajax({
          type: 'patch',
          url: '/queue/'+data,
          data: {
          id:data,
          _token:token.value
          },
          success: function (response) {
         
          }
  });
  return false;
}


function checkForm()
  {
var disable =  document.getElementById('sendButton');
   disable.disabled = true;
}
$('form input').on('keypress', function(e) {
    return e.which !== 13;
});

</script>
@endsection
