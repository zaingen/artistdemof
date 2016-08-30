@extends('master.master')
@push('css')
<style type="text/css">
.ui-menu .ui-menu-item a{
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration:none;
}
.ui-menu .ui-menu-item a:hover{
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;    
}
</style>


@endpush
@section('content')
<div class="row">
        <div id="artist-search-form">
            <h1>Search for an Artist</h1>
            <p>Type an artist name and click on "Search".</p>
            <form id="search-form">
                <input type="text" id="artist" value="" class="form-control" placeholder="Type an Artist Name"/>
            </form>
        </div>
        <div class="col-md-9 col-md-offset-2">
            <div id="results"></div>
        </div>
</div>
@endsection
@push('scripts')
<script src="<?php echo url('/'); ?>/js/tags.js" ></script>
<script type="text/javascript">

    /*var $_searchQuery = $('#artist');
    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        var re = new RegExp($.trim(this.term.toLowerCase()));
        var t = item.label.replace(re, "<span style='font-weight:600;color:#5C5C5C;'>" + $.trim(this.term.toLowerCase()) +
            "</span>");
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + t + "</a>")
            .appendTo(ul);
    };

    $_searchQuery.autocomplete({
        source: availableTags,
        select:   
            function(event, ui) {  
               $("input#artist").val(ui.item.value);
        },
        focus: function(event, ui) {
            $("input#artist").val(ui.item.value);
        },  
        minLength: 3, 

    });*/


    /*$("#artist").keyup(function(e){
        e.preventDefault();
        var artist =$('input#artist').val();
        var code = e.keyCode || e.which;
         if(code == 13 || code == 38 || code == 40 ) { //Enter keycode
            $("input#artist").val(artist);
        }else{
            $("input#artist").val(""); 
        }                
    });*/
    monkeyPatchAutocomplete();
    $( "#artist" ).autocomplete({
        source: availableTags,  
        select:   
            function(event, ui) {  
               $("input#artist").val(ui.item.value);
               $("#search-form").submit();
        },
        focus: function(event, ui) {
            $("input#artist").val(ui.item.value);
        },  
        minLength: 3, 
    });
    function monkeyPatchAutocomplete() {
      var oldFn = $.ui.autocomplete.prototype._renderItem;

      $.ui.autocomplete.prototype._renderItem = function( ul, item) {

          var t = String(item.value).replace(
            new RegExp('^'+this.term, "gi"),
            "<span class='ui-state-highlight'>$&</span>");

          return $( "<li></li>" )
              .data( "item.autocomplete", item )
              .append( "<a>" + t + "</a>" )
              .appendTo( ul );
      };
    }
    
    /// find template and compile it
        //templateSource = document.getElementById('results-template').innerHTML;
        //template = Handlebars.compile(templateSource);
        resultsPlaceholder = document.getElementById('results');
        playingCssClass = 'playing';
        audioObject = null;
        //console.log(templateSource);
        //console.log(template);

    var fetchTracks = function (albumId, callback) {
        $.ajax({
            url: 'https://api.spotify.com/v1/albums/' + albumId,
            success: function (response) {
                callback(response);
            }
        });
    };
    var getResult=function(queryurl){
        event.preventDefault();
        //console.log(queryurl);
        $.ajax({
            url: queryurl,
            success: function (response) {
                resultsPlaceholder.innerHTML="";
                var get_artis_data=pupulateArtistAlbumsData(response);
                resultsPlaceholder.innerHTML =populateArtistData(get_artis_data);
            }
        });
    };
    function populateArtistData(data) {
        /*var artist_wrap_html="";
        var album_wrap_html="";*/
        var final_html="";
        console.log(data);
        final_html+="<div class='container-fluid'>";
        if(data.artists.total ==0){
            final_html+="<div class='message'> No Results Found .</div>";
            return final_html;
        }
        //$.each( data, function( artists, artistdata ) {
            $.each( data.artists.items, function( items, itemresults ) {
                var artist_wrap_html="";
                var artist_id=itemresults.id;
                artist_wrap_html+="<div class='main-artist-wrap row' >";
                    artist_wrap_html+="<div class='col-md-4 '>";
                        var image="<?php echo url('/')."/images/no-image.png"; ?>";
                        if(itemresults.images.length > 0) {
                            image=itemresults.images[0].url;
                        }
                        artist_wrap_html+="<div data-artist-id='"+itemresults.id+"' class='artist-image cover' style='background-image:url("+image+")'></div>";
                    artist_wrap_html+="</div>";    
                    artist_wrap_html+="<div class='col-md-8 '>";
                        artist_wrap_html+="<h3> Discography </h3>";
                        artist_wrap_html+="<div class=''>";
                            artist_wrap_html+="<div class='row'>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Name : </label> ";
                                    artist_wrap_html+="<span class=''> "+itemresults.name+ "</span>";
                                artist_wrap_html+="</div>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Type : </label>";
                                    artist_wrap_html+="<span class=''> "+itemresults.type+" </span>";
                                artist_wrap_html+="</div>";
                            artist_wrap_html+="</div>";   
                            artist_wrap_html+="<div class='row'>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Popularity : </label>";
                                    artist_wrap_html+="<span class=''> "+itemresults.popularity+" </span>";
                                artist_wrap_html+="</div>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Followers : </label>";
                                    artist_wrap_html+="<span class=''> "+itemresults.followers.total+" </span>";
                                artist_wrap_html+="</div>";
                            artist_wrap_html+="</div>";   
                            artist_wrap_html+="<div class='row'>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Genres : </label>";
                                        var genres=(itemresults.genres.length > 0)?itemresults.genres.join(", "):"";
                                    artist_wrap_html+="<span class=''> "+genres+" </span>";
                                artist_wrap_html+="</div>";
                                artist_wrap_html+="<div class='col-md-6'>";
                                    artist_wrap_html+="<label class=''> Details : </label>";
                                    artist_wrap_html+="<span class=''>";
                                        //artist_wrap_html+="<a class='ablum-track' href='<?php echo url('/');?>/artistdetail?artistid="+itemresults.id+"'>";
                                        artist_wrap_html+="<a class='ablum-track' href='#'>";
                                            artist_wrap_html+='&nbsp'+ itemresults.name;
                                        artist_wrap_html+="</a>";
                                    artist_wrap_html+="</span>";
                                artist_wrap_html+="</div>";
                            artist_wrap_html+="</div>";   
                        artist_wrap_html+="</div>";
                    artist_wrap_html+="</div>";
                artist_wrap_html+="</div>"; // main artist row end 
                //console.log(itemresults.albums.items);
                var count=0;
                var album_wrap_html="";
                album_wrap_html+="<div class='main-artist-album-wrap'><h3>"+itemresults.name+" Albums</h3><div class='row'>";
                $.each( itemresults.albums.items, function( aitems, a_itemresults ) {
                    //console.log(itemresults.albums);
                    count++;
                    album_wrap_html+="<div class='col-md-4'>";
                        var a_image="<?php echo url('/')."/images/no-image.png"; ?>";
                        if(a_itemresults.images.length > 0) {
                            a_image=a_itemresults.images[0].url;
                        }
                        album_wrap_html+="<div data-album-id='"+a_itemresults.id+"' class='album-image cover' style='background-image:url("+a_image+")'></div>";
                        album_wrap_html+="<div class='album-detail'>";
                            album_wrap_html+="<span class='ablum-name'>";
                                album_wrap_html+=a_itemresults.name;
                            album_wrap_html+="</span>";
                            album_wrap_html+="<span class='album-track'>";
                                album_wrap_html+=" Total tracks ("+itemresults.albums.total+")";
                            album_wrap_html+="</span>";
                            album_wrap_html+="<div class='ablum-track-lnk'>";
                                album_wrap_html+="<a class='ablum-track' href='<?php echo url('/');?>/albumtracks?artistid="+artist_id+"&albumid="+a_itemresults.id+"'>";
                                    album_wrap_html+="View Tracks";
                                album_wrap_html+="</a>";
                            album_wrap_html+="</div>";
                        album_wrap_html+="</div>";
                    album_wrap_html+="</div>";
                    if(count===3){
                        count=0;
                        album_wrap_html+="</div><div class='row'>";
                    }
                });
                if(count <= 3){
                    album_wrap_html+="</div>";
                }
                album_wrap_html+="</div> "; // main-artist-album-wrap end
                final_html+=artist_wrap_html+album_wrap_html;
            });
            //artist_wrap_html+=album_wrap_html;
        //});
            final_html+="<div style='text-align:center;'>";
                final_html+="<ul class='pagination'>";
                if(data.artists.previous){
                    var prevlink='"'+data.artists.previous+'"';
                    final_html+="<li><a href='#' onclick='getResult("+prevlink+")'>Previous Result</a></li>";
                }
                if(data.artists.next){
                    var nextlink='"'+data.artists.next+'"';
                    final_html+="<li><a href='#' onclick='getResult("+nextlink+")'>Next Result</a></li>";
                }
                final_html+="</ul>";
            final_html+="</div>"; // pagination div end
        final_html+="</div>"; // main fluid div end
        //console.log(final_html);
        return final_html;
    }

    var populateResultData=function(data){
        //console.log(data);
        var album_wrap_html="";
        //console.log(data);
        if(data.albums.total ==0){
            album_wrap_html+="<div class='message'> No Results Found .</div>";
            return album_wrap_html;
        }
        $.each( data, function( albums, albumdata ) {
            var count=0;
            album_wrap_html+="<div class='row'>";
            //console.log(albumdata);
            $.each( albumdata.items, function( items, itemresults ) {
                count++;
                album_wrap_html+="<div class='album-wrap'>";
                    album_wrap_html+="<div data-album-id='"+itemresults.id+"' class='album-image cover' style='background-image:url("+itemresults.images[0].url+")'></div>";
                    album_wrap_html+="<div class='album-detail'>";
                        album_wrap_html+="<span class='ablum-name'>";
                            album_wrap_html+=itemresults.name;
                        album_wrap_html+="</span>";
                        album_wrap_html+="<span class='ablum-track'>";
                            album_wrap_html+="<a class='ablum-track' href='<?php echo url('/');?>/albumtracks?albumid="+itemresults.id+"'>";
                            //album_wrap_html+="<a class='ablum-track' href='#'>";
                                album_wrap_html+="Album Tracks";
                            album_wrap_html+="</a>";
                        album_wrap_html+="</span>";
                    album_wrap_html+="</div>";
                album_wrap_html+="</div>";
                if(count===3){
                    count=0;
                    album_wrap_html+="</div><div class='row'>";
                }
            });
            if(count <= 3){
                album_wrap_html+="</div>";
            }
            //console.log(album_wrap_html);
        });
        album_wrap_html+="<div style='text-align:center;'>";
        album_wrap_html+="<ul class='pagination'>";
        if(data.albums.previous){
            var prevlink='"'+data.albums.previous+'"';
            album_wrap_html+="<li><a href='#' onclick='getResult("+prevlink+")'>Previous Result</a></li>";
        }
        if(data.albums.next){
            var nextlink='"'+data.albums.next+'"';
            album_wrap_html+="<li><a href='#' onclick='getResult("+nextlink+")'>Next Result</a></li>";
        }
        album_wrap_html+="</ul>";
        album_wrap_html+="</div>";
        //console.log(album_wrap_html);
        return album_wrap_html;
    }

    function searchArtistAlbums(artist) {
        $.ajax({
            url: 'https://api.spotify.com/v1/search',
            data: {
                q: artist,
                type: 'artist'
            },
            async: false,
            success: function (response) {
                var get_artis_data=pupulateArtistAlbumsData(response);
                //console.log(get_artis_data);
                resultsPlaceholder.innerHTML =populateArtistData(get_artis_data);
            }
        });
    };

    function pupulateArtistAlbumsData(ar_al_data){ // artist album data
        var artist_albums = [];
        //repsonce_data=data;
        $.each( ar_al_data, function( artists, artistslist ) {
            $.each( artistslist.items, function( singleartist, singleartistdata ) {
                if(!("albums" in singleartistdata)) {
                     $.ajax({
                          url: 'https://api.spotify.com/v1/artists/'+singleartistdata.id+'/albums',
                          async:false,
                          success: function (response) {   
                           singleartistdata.albums = response;
                           artist_albums.push(singleartistdata);
                          }
                         });
                }
            });
        });
        return ar_al_data;
    }

    var getArtistAlbum=function(artistid){
        $.ajax({
            url: 'https://api.spotify.com/v1/artists/'+artistid+'/albums',
            async:false,
            success: function (response) {
                //console.log(response)
                return response;
            }
        });
    };

    $('#search-form').submit(function(e){
        e.preventDefault();
        searchArtistAlbums(document.getElementById('artist').value);

    });


    /*results.addEventListener('click', function (e) {
        var target = e.target;
        if (target !== null && target.classList.contains('cover')) {
            if (target.classList.contains(playingCssClass)) {
                audioObject.pause();
            } else {
                if (audioObject) {
                    audioObject.pause();
                }
                fetchTracks(target.getAttribute('data-album-id'), function (data) {
                    audioObject = new Audio(data.tracks.items[0].preview_url);
                    audioObject.play();
                    target.classList.add(playingCssClass);
                    audioObject.addEventListener('ended', function () {
                        target.classList.remove(playingCssClass);
                    });
                    audioObject.addEventListener('pause', function () {
                        target.classList.remove(playingCssClass);
                    });
                });
            }
        }
    });*/

    /*document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        searchArtistAlbums(document.getElementById('artist').value);
    }, false);*/
    /*(function() {
        function login(callback) {
            var CLIENT_ID = '7cd48e041ecf48adabebf07eb6f03caa';
            var REDIRECT_URI = 'http://localhost/myspotify/spotify.php';
            function getLoginURL(scopes) {
                return 'https://accounts.spotify.com/authorize?client_id=' + CLIENT_ID +
                  '&redirect_uri=' + encodeURIComponent(REDIRECT_URI) +
                  '&scope=' + encodeURIComponent(scopes.join(' ')) +
                  '&response_type=token';
            }
            alert(CLIENT_ID);
            
            var url = getLoginURL([
                'user-read-email'
            ]);
            
            var width = 450,
                height = 730,
                left = (screen.width / 2) - (width / 2),
                top = (screen.height / 2) - (height / 2);
        
            window.addEventListener("message", function(event) {
                var hash = JSON.parse(event.data);
                if (hash.type == 'access_token') {
                    callback(hash.access_token);
                }
            }, false);
            
            var w = window.open(url,
                'Spotify',
                'menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left
               );
        }

        function getUserData(accessToken) {
            return $.ajax({
                url: 'https://api.spotify.com/v1/me',
                headers: {
                   'Authorization': 'Bearer ' + accessToken
                }
            });
        }

        var templateSource = document.getElementById('result-template').innerHTML,
            template = Handlebars.compile(templateSource),
            resultsPlaceholder = document.getElementById('result'),
            loginButton = document.getElementById('btn-login');
        
        loginButton.addEventListener('click', function() {
            login(function(accessToken) {
                getUserData(accessToken)
                    .then(function(response) {
                        loginButton.style.display = 'none';
                        alert(response);
                        resultsPlaceholder.innerHTML = template(response);
                    });
                });
        });
})();
*/    
</script>
@endpush

