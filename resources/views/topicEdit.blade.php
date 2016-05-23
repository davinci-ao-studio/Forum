@extends('layouts.app')
<?php $loop = 0; ?>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                  <ul>
                      <p>Deze leervraag is {{$result->created_at->diffForHumans()}} gemaakt. </p>
                        {{Form::open(array('route' => array('topic.update', $result->id), 'method' => 'PATCH'))}}
                         {!! Form::text('title', $result->topic_title, ['class' => 'form-control', 'required']) !!}
                          <?php echo Form::textarea('description', $result->topic_description, ['class' => 'form-control', 'required']) ?>
                            @if($user->role == 1)
                           <input type="checkbox" name="notify"> Verstuur notificatie?<br>
                           @endif
                       <p><strong>Tags</strong></p>
                      @foreach($tags as $tag)    
                          <input type="checkbox" name="new_tags[]" value="<?= $tag->id?>" required> <?=$tag->tag_name?> <br>
                      @endforeach
                       
                      <p></p>                         
                        {!! Form::submit('Aanpassen', ['class' => 'btn btn-primary form-control']) !!}
                        <p>Gemaakt door <a  style="text-transform:capitalize;" href="/profile/<?=$result->user->id?>">{{$result->user->name}}</a></p>
                      @if(Auth::check())
                        <?php   $user = \Auth::user(); ?>
                      @endif
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
