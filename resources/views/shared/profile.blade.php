<div class="pure-g margintop2">
    <div class="pure-u-1 pure-u-sm-1-3">
        <div style="position: relative; z-index: 1">
            @if (isset($profile->user))
                <img class="pure-img"
                     src={{ action('PhotoController@getProfilePhoto', $profile->user->id) }} alt=""></img>
            @else
                <img class="pure-img"
                     src="https://static.licdn.com/scds/common/u/images/themes/katy/ghosts/person/ghost_person_200x200_v1.png"
                     width="200" height="200">
            @endif
            <button style="display: block; position: absolute; top: 0; left: 0; bottom: 0;width: 100%; max-width: 200px; background: rgba(200,200,200,0.65); border: 0">
                <span style="color: white"><i class="fa fa-plus" style="color:white"></i> Add a photo</span>
            </button>
        </div>
    </div>
    <div class="pure-u-1 pure-u-sm-2-3">
        <div class="pure-u-1" v-on:mouseenter="enableProfileEdit" v-on:mouseleave="disableProfileEdit">
            <div class="profile-column">
                <div class="profile-caption singleline">
                    <span id="namecell">{{ $profile->name or 'Your Name' }}</span>
                    <i class="fa fa-pencil editable pencil" v-show="profileData.editable || profileData.editing"
                       v-on:click="showNameEditor"></i>
                </div>
                <div class="profile-subcaption singleline">
                    <span>{{ $profile->weixin or 'weixin id' }}</span>
                    <i class="fa fa-pencil editable pencil" v-show="profileData.editable || profileData.editing"
                     v-on:click="showWeixinEditor"></i>
                </div>
                <div class="profile-address singleline margintop1">
                    <span>{{ $profile->address or 'your address' }}</span>
                    <i class="fa fa-pencil editable pencil" v-show="profileData.editable || profileData.editing"
                       v-on:click="showAddressEditor"></i>
                </div>
            </div>

            <div class="pure-u-1 margintop2">
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

    <div style="display: none">
        <div id="nameFormPopover">
            <name-form :on-submit="update"></name-form>
        </div>
        <div id="addressFormPopover">
            <address-form :on-submit="update"></address-form>
        </div>
        <div id="weixinFormPopover">
            <weixin-form :on-submit="update"></weixin-form>
        </div>
    </div>

</div>