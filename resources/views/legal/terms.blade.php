@extends('layouts.app',[
    "pagename"=>"Terms and Conditions"
])

@section('content')
<div class="container">
    <h1>Terms and Conditions</h1>
    <h3>Introduction</h3>
    <p>The service provided by {{config("app.name")}} is free to use and is provided as is. These terms and conditions are to outline what we expect of a user and what the user can expect of our service.</p>

    <h3>Information we provide</h3>
    <p>{{config("app.name")}} does not take any responsibility for any <u>server</u> information displayed on the site. Servers can be submitted freely by anyone and {{config("app.name")}} is not able to verify the information for each and every server.</p>
    <p>If there is any content (text, videos or images) that infringes copyright or is considered immoral / illegal then you must contact us immediatley either via social media or our email abuse@mcserver-list.net</p>
    
    <h3>Rules</h3>
    <p>We allow users to vote for servers. Votes can only be cast once per day, per server. We have systems in place to verify that a vote is legitimate using data outlined in our <a href="{{route('legal.privacy')}}">Privacy Policy</a>. This data is used to ensure votes are valid.</p>
    <p>If we find a user to have bypassed our checks and is "cheating" we reserve the right to prevent the user from accessing {{config("app.name")}} and all services owned by {{config("app.name")}}.</p>
    <p>If a user has submitted a server that doesn't belong to them, or has content that infringes copyright we reserve the right to remove this from our servers without notice.</p>
    <p>We do not accept "cracked" servers or allow "cracked" servers to be sponsored on our listing.</p>
    <p>When submitting a server the title field should only contain your servers name, exluding any plugins or requests these can be placed in the description if you wish.</p>
    <p>You are not allowed to use any kinds of scripts or macros to communicate with this site. For instance, scripts trying to vote automatically or edit servers automatically. If we detect such activity you & your server will be removed without notice.</p>

    <h3>General</h3>
    <p>Servers offline for more 30 days will automatically be removed from our listing and any servers that are offline will not be shown in our server listing.</p>


    <br/><br/>
    <p>By continuing to use {{config("app.name")}} you agree to our terms and conditions, you should also take a look at our <a href="{{route('legal.privacy')}}">Privacy Policy</a> for information we may collect and how we will use it.</p>
</div>
@endsection