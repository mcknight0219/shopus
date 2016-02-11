<div class="pure-g margintop2">
    <div class="pure-u-1 pure-u-sm-1-3">
        <img class="pure-img" src={{ action('PhotoController@getPhoto', $profile->user->id) }} alt=""></img>
    </div> 
    <div class="pure-u-1 pure-u-sm-2-3">
        <div class="pure-u-1 margintop1">
            <div class="pure-g">
                <div class="pure-u-1">
                    <span class="cardname marginleft1">Weixin:</span>
                    <span id="profileWeixinId" class="cardval">{{ $profile->weixin }}</span>
                </div>

                <div class="pure-u-1 margintop1">
                    <span class="cardname marginleft1">Address:</span>
                    <span class="cardval">{{ $profile->address }}</span>
                </div>

                <div class="pure-u-1 margintop1">
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1">City:</span>
                        <span class="cardval">{{ $profile->city }}</span>
                    </div>
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1">State:</span>
                        <span class="cardval">{{ $profile->state }}</span>
                    </div>
                </div>
                </div>

                <div class="pure-u-1 margintop1">
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-shopping-bag"></i></span>
                        <span class="cardval">5</span>
                    </div>
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-usd"></i></span>
                        <span class="cardval">109.12</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>