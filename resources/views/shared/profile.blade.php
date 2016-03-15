<div class="pure-g margintop2" v-on:mouseenter="enableProfileEdit" v-on:mouseleave="disableProfileEdit">
    <div class="pure-u-1 pure-u-sm-1-3">
        <div style="position: relative; z-index: 1">
                <img class="pure-img"
                     v-bind:src="profileData.url" alt="" width="200" height="200"></img>
            <button @click="profileData.showModal=true" style="display: block; position: absolute; top: 0; left: 0; bottom: 0;width: 100%; max-width: 200px; background: rgba(200,200,200,0.65); border: 0">
                <span v-show="profileData.editable" style="color: white"><i class="fa fa-plus-circle" style="color:white"></i> Add a photo</span>
            </button>
            <modal :show.sync="profileData.showModal"></modal>
        </div>
    </div>
    
    <div class="pure-u-1 pure-u-sm-2-3">
        <div class="pure-u-1" >
            <div class="profile-column">
                <div class="profile-caption singleline">
                    <span id="namecell">@{{ profileData.name }}</span>
                    <i class="fa fa-pencil editable pencil" v-show="profileData.editable || profileData.editing"
                       v-on:click="showNameEditor"></i>
                </div>
                <div class="profile-subcaption singleline">
                    <span id="weixincell">@{{ profileData.weixin }}</span>
                    <i class="fa fa-pencil editable pencil" v-show="profileData.editable || profileData.editing"
                     v-on:click="showWeixinEditor"></i>
                </div>
                <div class="profile-address singleline margintop1">
                    <span id="addresscell">@{{ address }}</span>
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
            <name-form :profile.sync="profileData"></name-form>
        </div>
        <div id="addressFormPopover">
            <address-form :profile.sync="profileData"></address-form>
        </div>
        <div id="weixinFormPopover">
            <weixin-form :profile.sync="profileData"></weixin-form>
        </div>
    </div>
</div>

<div class="pure-g margintop1" v-show="true">
    <div class="pure-u-1-3">
        <img src="http://www.qrstuff.com/images/sample.png" alt="" width="150px" height="150px">
    </div>
    <div class="pure-u-2-3">
        <p class="centertext">扫一扫，添加我们的公众号。就可以开卖你的商品了 ！</p>   
    </div>
</div>
