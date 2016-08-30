@extends('master.master')
@section('content')
<div id="page-content-wrapper" class="">
    <div class="row">
        <div class="col-md-9 col-md-offset-2">
            @if(Session::has('message'))
                <p class="{{ Session::get('alert-class') }}">{{ Session::get('message') }}</p>
            @endif    
	        <div class="container-fluid">
                <div class='main-artist-wrap-t row' >
                    <div class='col-md-4 '>
                        <?php 
                            $image=url('/')."/images/no-image.png";
                            if(isset($artist_data->images[0]->url)) $image=$artist_data->images[0]->url;
                        ?>
                        <div data-artist-id="<?php echo $artist_data->id;?>" class="artist-image cover" style="background-image:url('<?php echo $image ?>')">
                        </div>
                    </div>
                    <div class='col-md-8'>
                        <h3> Artist Discography </h3>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <label class=''> Name : </label> 
                                    <span class=''><?php echo $artist_data->name;?> </span>
                                </div>
                                <div class='col-md-6'>
                                    <label class=''> Type : </label>
                                    <span class=''> <?php echo $artist_data->type; ?> </span>
                                </div>
                            </div> 
                            <div class='row'>
                                <div class='col-md-6'>
                                    <label class=''> Popularity : </label>
                                    <span class=''> <?php echo $artist_data->popularity; ?> </span>
                                </div>
                                <div class='col-md-6'>
                                    <label class=''> Followers : </label>
                                    <span class=''> <?php echo $artist_data->followers->total; ?> </span>
                                </div>
                            </div> 
                            <div class='row'>
                                <div class='col-md-6'>
                                    <label class=''> Genres : </label>
                                    <?php $genres=(count($artist_data->genres) > 0)?implode(',',$artist_data->genres):"" ?>
                                    <span class=''> <?php echo $genres; ?> </span>
                                </div>
                                <div class='col-md-6'>
                                    <label class=''> Details : </label>
                                    <span class=''>
                                        <a class='ablum-track' href='<?php echo url('/');?>/artistdetail?artistid=<?php echo $artist_data->id; ?>'>
                                            &nbsp<?php echo $artist_data->name;  ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                    </div>
                </div>
                    <div class='main-artist-wrap-t row' >
                        <div class='col-md-4 '>
                            <?php 
                                $image=url('/')."/images/no-image.png";
                                if(isset($album_tracks->images[0]->url)) $image=$album_tracks->images[0]->url;
                            ?>
                            <div data-artist-id="<?php echo $album_tracks->id;?>" class="artist-image cover" style="background-image:url('<?php echo $image ?>')">
                            </div>
                        </div>
                        <div class='col-md-8'>
                            <h3> Album Discography </h3>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <label class=''> Name : </label> 
                                        <span class=''><?php echo $album_tracks->name;?> </span>
                                    </div>
                                    <div class='col-md-6'>
                                        <label class=''> Type : </label>
                                        <span class=''> <?php echo $album_tracks->type; ?> </span>
                                    </div>
                                </div> 
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <label class=''> Popularity : </label>
                                        <span class=''> <?php echo $album_tracks->popularity; ?> </span>
                                    </div>
                                    <div class='col-md-6'>
                                        <label class=''> Release Year : </label>
                                        <span class=''> <?php echo $album_tracks->release_date; ?> </span>
                                    </div>
                                </div> 
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <label class=''> Genres : </label>
                                        <?php $genres=(count($album_tracks->genres) > 0)?implode(',',$album_tracks->genres):"" ?>
                                        <span class=''> <?php echo $genres; ?> </span>
                                    </div>
                                    <div class='col-md-6'>
                                        <label class=''> Total Tracks : </label>
                                        <span class=''>
                                                <?php echo $album_tracks->tracks->total;  ?>
                                        </span>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="main-track-wrap-t row">
                        <div class="row">
                            <?php $count=0; //dd($album_tracks->tracks->items); ?>
                            <?php foreach ($album_tracks->tracks->items as $albumtracks) {  $count++; ?>
                                    <div class="col-md-3 album-track-detail">
                                        <h4> <?php echo 'Track Detail'; ?> </h4>
                                        <ul>
                                            <li>
                                                Name : <?php echo $albumtracks->name;  ?>
                                            </li>
                                            <li>
                                                Time : <?php echo formatMilliseconds($albumtracks->duration_ms); ?>
                                            </li>
                                            <li>
                                                Type : <?php echo $albumtracks->type; ?>
                                            </li>
                                            <li>
                                                Number : <?php echo $albumtracks->track_number; ?>
                                            </li>
                                            <li>
                                                <a href="<?php echo $albumtracks->external_urls->spotify; ?>" target="_blank"><?php echo 'Listen Track'; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php 
                                        if($count===3){
                                            $count=0;
                                        ?>
                                            </div><div class='row'>
                                        <?php } ?>
                            <?php } ?>
                            <?php  if($count <= 3){ ?>
                                </div>
                            <?php } ?>
                    </div>
                </div>                    
	        </div>
        </div>
    </div>
</div>
@endsection
<?php 
function formatMilliseconds($milliseconds) {
    $seconds = floor($milliseconds / 1000);
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $milliseconds = $milliseconds % 1000;
    $seconds = $seconds % 60;
    $minutes = $minutes % 60;

    $format = '%u:%02u:%02u.%03u';
    $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
    return rtrim($time, '0');
}
function sec_to_time($seconds) {
  $hours = floor($seconds / 3600);
  $minutes = floor($seconds % 3600 / 60);
  $seconds = $seconds % 60;

  return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
} 
?>
@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
	var albumid='<?php echo $album_id; ?>';
    var client_id='<?php echo config("spotify.s_client_id"); ?>'; 
    var client_secret='<?php echo config("spotify.s_client_secret"); ?>'; 
    var redirect_uri='<?php echo config("spotify.s_redirect_uri"); ?>'; 
	console.log(client_id);
    //authenticateSpotifyUser(client_id,client_secret,redirect_uri)
    var authenticateSpotifyUser = function (artist) {
            $.ajax({
                url: 'https://api.spotify.com/v1/search',
                data: {
                    q: artist,
                    type: 'album'
                },
                success: function (response) {
                    //console.log(response);
                    //resultsPlaceholder.innerHTML = template(response);
                    resultsPlaceholder.innerHTML =populateResultData(response);
                }
            });
        };    
	var getAlbumTracks = function (albumid) {
        $.ajax({
            url: 'https://api.spotify.com/v1/albums/'+albumid+'/tracks',
            success: function (response) {
            	console.log(response);
            }
        });
    };
    //getAlbumTracks(albumid);
});
</script>
@endpush



