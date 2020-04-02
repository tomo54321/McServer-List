@extends('layouts.app',[
"pagename"=>"Edit Server"
])

@section('content')
<div class="container">
    <h1>Edit Server</h1>

    @if(\Session::has("success"))
        <div class="alert alert-success"><strong>Success</strong> {{\Session::get("success")}}</div>
    @endif

    <p>Fields marked with a <span class="text-danger">*</span> are required.</p>
    <form action="{{route('server.update', ["server"=>$server->id])}}" method="POST" id="create-server-form" enctype="multipart/form-data">
        @csrf
        @method("put")
        <div class="form-group">
            <label for="name">Server Name<span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control @error('name')is-invalid @enderror"
                placeholder="Server Name" value="{{old('name') ?? $server->name}}" autocomplete="off" required />
            @error("name")
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="ip">Server IP<span class="text-danger">*</span></label>
                    <input type="text" name="ip" id="ip" class="form-control @error('ip')is-invalid @enderror"
                        placeholder="Server IP Address" value="{{old('ip') ?? $server->ip}}" autocomplete="off" required />
                    @error("ip")
                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="port">Port<span class="text-danger">*</span></label>
                    <input type="number" name="port" id="port" class="form-control @error('port')is-invalid @enderror"
                        placeholder="Server IP Address" value="{{old('port')??$server->port}}" autocomplete="off" required />
                    @error("port")
                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group" id="server-verify">
                    <button type="button" class="btn btn-success">Verify Server</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="banner">Add your server banner</label>
            @if($server->has_banner)
                <span class="text-muted d-block">Current Banner</span>
                <img src="{{asset( "storage/".$server->user_id."/".$server->banner_path )}}" alt="{{$server->name}}" class="d-block mb-3"/>
            @endif
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="banner" accept="image/x-png,image/gif,image/jpeg" name="banner" />
                <label class="custom-file-label" for="banner">Server Banner</label>
            </div>
            @error("banner")
            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="header">Upload a screenshot of your server</label>
            @if($server->has_header)
                <span class="text-muted d-block">Current Header</span>
                <img src="{{asset( "storage/".$server->user_id."/".$server->has_header )}}" alt="{{$server->name}}" class="d-block mb-3" height="100px"/>
            @endif
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="header" accept="image/x-png,image/gif,image/jpeg" name="header" />
                <label class="custom-file-label" for="header">Server Heading Image</label>
            </div>
            @error("header")
            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="youtubeid">YouTube Trailer</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">https://youtube.com/watch?v=</span>
                </div>
                <input type="text" name="youtubeid" id="youtubeid" class="form-control @error('youtubeid')is-invalid @enderror"
                    placeholder="Youtube Video ID" value="{{old('youtubeid') ?? $server->youtube_id}}" autocomplete="off" />
            </div>
            @error("youtubeid")
            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="website">Server Website</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">https://</span>
                </div>
                <input type="text" name="website" id="website" class="form-control @error('website')is-invalid @enderror"
                    placeholder="www.myserver.com" value="{{old('website') ?? $server->website}}" autocomplete="off" />
            </div>
            @error("website")
            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="desc">Server Description<span class="text-danger">*</span></label>
            <textarea id="desc" name="desc" class="form-control @error('desc')is-invalid @enderror">{{old("desc") ?? $server->description}}</textarea>
            @error("desc")
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="votifier" name="votifier">
            <label class="custom-control-label" for="votifier">Support Votifier?</label>
        </div>
        <div id="votifier_support" class="jumbotron p-3 mt-3" style="display:none;">

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="vote_ip">Votifier IP</label>
                        <input type="text" name="vote_ip" id="vote_ip" class="form-control @error('vote_ip')is-invalid @enderror"
                            placeholder="Votifier IP Address" value="{{old('vote_ip') ?? $server->votifier_ip}}" autocomplete="off" />
                        @error("vote_ip")
                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="vote_port">Votifier Port</label>
                        <input type="number" name="vote_port" id="vote_port" class="form-control @error('vote_port')is-invalid @enderror"
                            placeholder="Votifier Port" value="{{old('vote_port') ?? $server->votifier_port}}" autocomplete="off" />
                        @error("vote_port")
                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="votifier_key">Votifier Key</label>
                <textarea class="form-control @error('votifier_key') is-invalid @enderror" style="resize: none;" name="votifier_key" id="votifier_key" placeholder="Please paste your votifier key here...">{{$server->votifier_key}}</textarea>
                @error("votifier_key")
                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                @enderror
            </div>

        </div>

        <h5 class="mt-3">Add Tags<span class="text-danger">*</span></h5>
        <small class="d-block text-muted">Min 1 &amp; Max 5</small>
        <div class="row mb-4">
        @foreach($tags as $tg)
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input server-tag-box" data-tag="{{$tg->id}}" id="tag-{{$tg->id}}" @if($server_tags->contains("tag_id", $tg->id)) checked @endif>
                    <label class="custom-control-label" for="tag-{{$tg->id}}">{{$tg->name}}</label>
                </div>
            </div>
        @endforeach

        @error("tags")
            <div class="invalid-feedback d-block">{{$message}}</div>
        @enderror
        </div>
        
        <div class="form-group">
            {!! ReCaptcha::htmlFormSnippet() !!}
            @error("g-recaptcha-response")
                <div class="invalid-feedback d-block">{{$message}}</div>
            @enderror
        </div>

        <hr />
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <span class="text-muted">By updating your server you agree all content submitted is owned by you or you have written permission to post</span>
                </div>
                <div class="col-6 text-right">
                    <input type="submit" class="btn btn-success" value="Update Server" />
                </div>
            </div>
        </div>

        @foreach($server_tags as $stag)
        <input type="hidden" name="tags[]" value="{{$stag->tag->id}}" data-tag-item="{{$stag->tag->id}}">
        @endforeach
    </form>
</div>
@endsection

@section("inline-script")
<script>
$(document).ready(function () {

    $('#banner, #header').on('change',function(){
        var fileName = $(this).val();
        $(this).next('.custom-file-label').text(fileName);
    });

    $("#votifier").on("change", function () {
        if ($(this).is(":checked")) {
            $("#votifier_support").slideDown("fast");
        } else {
            $("#votifier_support").slideUp("fast");
        }
    });

    $("#ip, #port").on("change", function () {
        $("#server-verify.form-group button").text("Verify Server").prop("disabled", false);
    });

    $(".server-tag-box").on("change", function (e) {
        var tag = $(this).data("tag");
        if (!isNaN(tag)) {
            if ($(this).is(":checked")) {
                
                if($("input[name='tags[]']").length == 5){
                    $(this).prop("checked", false);
                    e.preventDefault();
                    return;
                }

                if ($("input[data-tag-item='" + tag + "']").length < 1) {
                    var tag_el = $("<input type='hidden' name='tags[]' />");
                    tag_el.val(tag).attr("data-tag-item", tag);
                    $("#create-server-form").append(tag_el);
                }

            } else {
                if ($("input[data-tag-item='" + tag + "']").length >0) {
                    $("input[data-tag-item='" + tag + "']").remove();
                }
            }
        }

    });

    $("#server-verify.form-group button").click(function () {
        $("#ip").removeClass("is-invalid");
        $("#ip").parent().children(".invalid-feedback").remove();
        if ($("#ip").val() == "") {
            $("#ip").addClass("is-invalid");
            $("#ip").parent().append("<div class='invalid-feedback' role='alert'>Please enter your server's ip.</div>");
            return;
        }

        $(this).text("Pinging...").prop("disabled", true);
        var btn = $(this);
        var invalid_message = $("<div class='invalid-feedback' role='alert'></div>");

        axios.get("/api/server/ping", {
            params: {
                ip: $("#ip").val(),
                port: $("#port").val() ?? "25565"
            }
        }).then(function (res) {
            if (res.data.success) {
                $(btn).prop("disabled", true).text("Server is online");
            } else {
                invalid_message.text(res.data.message);
                $("#ip").addClass("is-invalid");
                $("#ip").parent().append(invalid_message);
                $(btn).text("Verify Server").prop("disabled", false);
            }
        })
            .catch(function (error) {
                invalid_message.text(typeof error.response != "undefined" ? error.response.data.message : error.message);
                $("#ip").addClass("is-invalid");
                $("#ip").parent().append(invalid_message);
                $(btn).text("Verify Server").prop("disabled", false);
            });
    });
});
</script>
@endsection