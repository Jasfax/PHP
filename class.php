<?php

class RoPHP
{
    protected   $strEncryption      =   'sha256'    ;
    protected   $strCookieJar       =   'cookies'   ;
    protected   $boolHTTPS          =   true        ;   //If you experience any errors regarding HTTPs then please report it!
    public      $currentUser        =   null        ;
    
    
    function __construct( )
    {
        if ( ! function_exists( 'curl_init' ) ) die( 'Please install curl!' ) ;
        
        $this->strCookieJar = dirname ( __FILE__ ) . '/' . $this->strCookieJar . '/' ;
        
        if ( ! file_exists( $this->strCookieJar ) )
            mkdir( $this->strCookieJar, 0777, true );
    }
    
    public function GetCountry( $strCountry )
    {
        $arrList   =   array   (
                                'United States'         =>  1,
                                'Germany'               =>  2,
                                'Netherlands'           =>  3,
                                'France'                =>  4,
                                'Spain'                 =>  5,
                                'Italy'                 =>  6,
                                'Ireland'               =>  7,
                                'Portugal'              =>  8,
                                'Canada'                =>  9,
                                'United Kingdom'        =>  10,
                                'Australia'             =>  11,
                                'New Zealand'           =>  12,
                                'Brazil'                =>  13,
                                'Philippines'           =>  14,
                                'Denmark'               =>  15,
                                'Sweden'                =>  16,
                                'United Arab Emirates'  =>  17,
                                'Poland'                =>  18,
                                'Malaysia'              =>  19,
                                'Turkey'                =>  20,
                                'Norway'                =>  21,
                                'Romania'               =>  22,
                                'Thailand'              =>  23,
                                'Singapore'             =>  24,
                                'Mexico'                =>  25,
                                'Saudi Arabia'          =>  26,
                                'Belgium'               =>  27,
                                'Lithuania'             =>  28,
                                'Israel'                =>  29,
                                'Indonesia'             =>  30,
                                'Russia'                =>  31,
                                'Finland'               =>  32,
                            );
                            
        if( array_key_exists( $strCountry, $arrList ) )
            return $arrList[ $strCountry ];
                
        return 1;       
    }
    
    public function GetPrivacySetting( $strType, $intID )
    {
        $arrList   =   array   (
                                'SocialNetworksVisibilityPrivacy'   =>  array   (
                                                                                    'AllUsers',
                                                                                    'FriendsFollowingAndFollowers',
                                                                                    'FriendsAndFollowing',
                                                                                    'Friends',
                                                                                    'NoOne',
                                                                                ),
                                                    
                                'ChatVisibilityPrivacy'             =>  array   (
                                                                                    'All',
                                                                                    'Followers',
                                                                                    'Following',
                                                                                    'Friends',
                                                                                    'Noone',
                                                                                    'Disabled'
                                                                                ),
                                                    
                                'PrivateMessagePrivacy'             =>  array   (
                                                                                    'All',
                                                                                    'Followers',
                                                                                    'Following',
                                                                                    'Friends',
                                                                                    'NoOne'
                                                                                ),
                                                    
                                'PartyInvitePrivacy'                =>  array   (
                                                                                    'All',
                                                                                    'Followers',
                                                                                    'Following',
                                                                                    'Friends',
                                                                                    'Noone',
                                                                                    'Disabled'
                                                                                ), 
                                                    
                                'PrivateServerInvitePrivacy'        =>  array   (
                                                                                    'AllAuthenticatedUsers',
                                                                                    'FriendsFollowingAndFollowers',
                                                                                    'FriendsAndFollowing',
                                                                                    'Friends',
                                                                                    'NoOne'
                                                                                ), 
                                                    
                                'FollowMePrivacy'                   =>  array   (
                                                                                    'All',
                                                                                    'Followers',
                                                                                    'Following',
                                                                                    'Friends',
                                                                                    'Noone'
                                                                                ),
                            );
                
        if( array_key_exists( $intID, $arrList[ $strType ] ) )
            return $rrList[ $strType ][ $intID ];
    }
    
    public function GetGenreSetting( $strType )
    {
        $arrList   =   array   (
                                'All'           =>  1,
                                'Building'      =>  19,
                                'Horror'        =>  11,
                                'Town and City' =>  7, 
                                'Military'      =>  17,
                                'Comedy'        =>  15,
                                'Medieval'      =>  8,
                                'Adventure'     =>  13,
                                'Sci-Fi'        =>  9,
                                'Naval'         =>  12,
                                'FPS'           =>  20,
                                'RPG'           =>  21,
                                'Sports'        =>  14,
                                'Fighting'      =>  10,
                                'Western'       =>  16,
                            );
                            
 
        if( array_key_exists( $strType, $arrList ) )
            return $arrList[ $strType ];
                
        return 1;       
    }
    
    public function GetGender( $strGender )
    {
        return ( ( strtoupper( $strGender ) === 'FEMALE' ) ? 3 : 1 ) ;
    }
    
    public function GetLanguage( $strLanguage )
    {
        return ( ( strtoupper( $strLanguage ) === 'GERMAN' ) ? 3 : 1 ) ;
    }
    
    public function RemoveCookie( $strUser = null )
    {
        $strUser = ( $strUser === null ? $this->currentUser : $strUser ) ;
        
        unlink( $this->GetCookie( $strUser ) ) ;
    }
    
    public function GetCookie( $strUser = null )
    {
        $strUser = ( $strUser === null ? $this->currentUser : $strUser ) ;
        
        return ( $this->strCookieJar . ( ( $this->strEncryption === null ) ? $strUser : hash( $this->strEncryption, $strUser ) ) ) ;
    }
    
    public function GetToken( $strToken, $strCache = null )
    {
        switch ( strtoupper( $strToken ) ) {
            case 'VERIFICATION':
                $objPreg = preg_match( '/<input name="__RequestVerificationToken" type="hidden" value="(.*?)"/', $this->NetworkRequest( 'm.roblox.com/home', null, true ), $arrMatches ) ;
                break;
            case 'CSRF':
                $objPreg = preg_match( "/Roblox\.XsrfToken\.setToken\('(.*?)'\)/", $this->NetworkRequest( 'www.roblox.com/home', null, true ), $arrMatches ) ;
                break;
            default:
                $objPreg = preg_match( '/\<input type="hidden" name="' . $strToken . '" id="' . $strToken .'" value="(.*?)"/', $strCache, $arrMatches ) ;
                break;
        }
        
        if ( ! $arrMatches )
            return false ;
    
        return $arrMatches[ 1 ] ;
    }
    
    public function NetworkRequest( $strURL, $arrData = null, $boolCookie = false, $strToken = null, $strHeader = null)
    {
        $c = curl_init( ) ;
   
        $strURL = ( ( $this->boolHTTPS and isset( $this->currentUser ) and $boolCookie ) ? 'https://' : 'http://' ) . $strURL ;
        
        curl_setopt( $c, CURLOPT_URL, $strURL ) ;
        curl_setopt( $c, CURLOPT_REFERER, $strURL ) ;
        curl_setopt( $c, CURLOPT_USERAGENT, 'RoPHP/1.2b' ) ;
        curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false ) ;
        curl_setopt( $c, CURLOPT_SSL_VERIFYHOST, false ) ;
        curl_setopt( $c, CURLOPT_FOLLOWLOCATION, ( ( ini_get( 'open_basedir' ) !== null ) ? false : true ) ) ;
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
        
        if ( $arrData !== null )
        {
            curl_setopt( $c, CURLOPT_POST, true ) ;
            curl_setopt( $c, CURLOPT_POSTFIELDS, $arrData ) ;
        }
        
        if ( $boolCookie )
        {
            curl_setopt( $c, CURLOPT_COOKIEFILE, $this->GetCookie( ) ) ;
            curl_setopt( $c, CURLOPT_COOKIEJAR, $this->GetCookie( ) ) ;
        }
        
        if ( $strToken !== null )
        {
            curl_setopt( $c, CURLOPT_HTTPHEADER, array( 'Connection: keep-alive', 'X-CSRF-TOKEN: ' . $strToken, 'X-Requested-With: XMLHttpRequest', ( ( $strHeader !== null  ) ? ( 'Content-Type: ' . $strHeader ) : null ) ) ) ;
        }
        
        $arrInfo = curl_getinfo( $c ) ;

        if ( $arrInfo[ 'url' ] !== $strURL )
            return $this->NewtorkRequest( $arrInfo[ 'url' ], $arrData, $boolCookie, $strToken, $strHeader ) ;
            
        $arrData = curl_exec ( $c ) ;

        if ( $arrInfo[ 'http_code' ] === 200 )
            return true ;
        elseif ( ( $arrInfo[ 'http_code' ] == 500 ) or ( $arrInfo[ 'http_code' ] === 403 ) )
            return false ;
        
        return $arrData ;
    }
    
    public function XPath( $strUrl, $strPath )
    {
        libxml_use_internal_errors( true ) ;
        $objDom = new DomDocument ;
        $objDom->loadHTML( $this->NetworkRequest( $strUrl ) ) ;
        $objXPath = new DomXPath( $objDom ) ;
        return $objXPath->query( $strPath ) ;
    }
    
    public function Is( $arr, $val )
    {
        return isset( $arr[ $val ] ) ? $arr[ $val ] : false ;
    }
    
    
    /*
    
        User Lib
        
    */
    
    public function GetUserArray( $varData )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/' . ( is_string( $varData ) ? 'get-by-username?username=' : null ) . $varData ), true ) ;
    }
    
    public function GetUserID( $varData )
    {
        return $this->Is( $this->GetUserArray( $varData ), 'Id' ) ;
    }
    
    public function IsUserOnline( $varData )
    {
        return $this->Is( $this->GetUserArray( $varData ), 'IsOnline' ) ;
    }
    
    public function GetUsername( $intID )
    {
        return $this->Is( $this->GetUserArray( $intID ), 'Username' ) ;
    }
    
    public function GetUsernames( $intID )
    {
        // TBA
    }
    
    public function GetUserAbout( $intID )
    {
        // TBA
    }
    
    public function GetUserAge( $intID )
    {
        // TBA
    }
    
    public function IsFollower( $intID, $intTarget )
    {
        return ( ( strpos( json_decode( $this->NetworkRequest( 'http://api.roblox.com/user/following-exists?userId=' . $intID . '&followerUserId=' . $intTarget, null, true ) )[ 'isFollowing' ] , 'true' ) === false ) ? false : true ) ;
    }
    
    public function IsFriend( $intID, $intTarget )
    {
        return ( ( strpos( $this->NetworkRequest( 'http://www.roblox.com/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerId=' . $intID . '&userId=' . $intTarget, null, true ) , 'true' ) === false ) ? false : true ) ;
    }
    
    public function GetUserPlaces( $intID )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'http://www.roblox.com/Contests/Handlers/Showcases.ashx?userId=' . $intID ) ), 'Showcase' ) ;
    }
    
    public function GetFriends( $intID, $intPage = 1 )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/' . $intID . '/friends?&page=' . $intPage ), true ) ;
    }
    
    public function GetFrienshipCount( $intID )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/get-friendship-count&userId=' . $intID ), true ) ;
    }
    
    public function GetFollowers( $intID, $intPage = 1 )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/followers?&page=' . $intPage  . '&userId=' . $intID ), true ) ;
    }
    
    public function GetGroups( $intID )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/' . $intID . '/groups' ), true ) ;
    }
    
    public function GetAssetVersions( $intID, $intPage = 1 )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'api.roblox.com/assets/' . $intID . '/versions?&page=' . $intPage ), true ), 'count' ) ;
    }
    
    
    /*
    
        Group Lib
    
    */
    
    public function GetGroupArray( $intID )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/groups/' . $intID ), true ) ;
    }
    
    public function GetGroupInfo( $intID )
    {
        return $this->GetGroupArray( $intID ) ;
    }
    
    public function GetGroupOwner( $intID )
    {
        return $this->Is( $this->Is( $this->GetGroupArray( $intID ), [ 'Owner' ] ), [ 'Id' ] ) ;
    }
    
    public function GetGroupName( $intID )
    {
        return $this->Is( $this->GetGroupArray( $intID ), 'Name' ) ;
    }
    
    public function GetGroupMemberCount( $intID )
    {
        foreach ( $this->XPath( 'www.roblox.com/Groups/group.aspx?gid=' . $intID, "//div[@id='MemberCount']" ) as $i => $objNode )
            return intval( str_replace( 'Members: ', null, $objNode->nodeValue ) ) ;
    }
    
    public function GetGroupDescription( $intID )
    {
        return $this->Is( $this->GetGroupArray( $intID ), 'Description' ) ;
    }
    
    public function GetGroupEmblem( $intID )
    {
        return $this->Is( $this->GetGroupArray( $intID ), 'EmblemUrl' ) ;
    }
    
    public function GetGroupEnemies( $intID )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'api.roblox.com/groups/' . $intID . '/enemies' ), true ), 'Groups' ) ;
    }
    
    public function GetGroupAllies( $intID )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'api.roblox.com/groups/' . $intID . '/allies' ), true ), 'Groups' ) ;
    }
    
    public function GetGroupRoles( $intID )
    {
        return $this->Is( $this->GetGroupArray( $intID ), 'Roles' ) ;
    }
    
    public function IsInGroup( $intID, $varTarget )
    {
        return boolval( $this->NetworkRequest( 'www.roblox.com/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ) . '&groupid=' . $intID ) ) ;
    }
    
    public function GetRoleInGroup( $intID, $varTarget )
    {
        return $this->NetworkRequest( 'www.roblox.com/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ) . '&groupid=' . $intID ) ;
    }
    
    public function GetRankInGroup( $intID, $varTarget )
    {
        return intval( $this->NetworkRequest( 'www.roblox.com/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ) . '&groupid=' . $intID ) ) ;
    }
    
    public function GetGroupPageRoles( $intID )
    {
        $arrPush = array( ) ;
        
        foreach ( $this->XPath( 'www.roblox.com/Groups/group.aspx?gid=' . $intID, "//select[@id='ctl00_cphRoblox_rbxGroupRoleSetMembersPane_dlRolesetList']/*" ) as $i => $objNode )
        {
            $arrPush[ $objNode->nodeValue ] = intval( $objNode->getAttribute( 'value' ) ) ;
        }
        return $arrPush ;
    }
    
    public function GetPrimaryGroup( $varTarget )
    {
        return $this->NetworkRequest( 'www.roblox.com/Groups/GetPrimaryGroupInfo.ashx?users=' . ( ! is_string( $varTarget ) ? $this->GetUsername( $varTarget ) : $varTarget ) ) ;
    }
    
    /*
    
        Auth Lib
            /User
        
    */
    
    public function DoLogin( $strPassword )
    {
        return $this->NetworkRequest( 'm.roblox.com/Login', array( 'UserName' => $this->currentUser, 'Password' => $strPassword ), true ) ;
    }
    
    public function Logout( )
    {
        return $this->NetworkRequest( 'api.roblox.com/sign-out/v1', array( ), true ) ;
    }
    
    public function IsLoggedIn( )
    {
        if( $this->NetworkRequest( 'www.roblox.com/Game/GetCurrentUser.ashx', null, true ) !== 'null' )
            return true ;
            
        $this->RemoveCookie( ) ;
        return false ;
    }
    
    // Untested
    public function ModifyAccount( )
    {
        
    }
    
    // Untested
    public function ChangePassword( $strOldPassword, $strNewPassword )
    {
        if( $this->NetworkRequest( 'www.roblox.com/account/changepassword', array( 'oldPassword' => $strOldPassword, 'newPassword' => $strNewPassword, 'confirmNewPassword' => $strNewPassword ), true, $this->GetToken( 'CSRF' ) ) === 'true' )
            return true ;
            
        return false ;
    }
    
    public function GetUserFunds( )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/my/balance', null, true ), true ) ;
    }
    
    public function SetFeeling( $strFeeling )
    {
        return ( strpos( $this->NetworkRequest( 'm.roblox.com/Account/SetStatus', array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION' ) , 'Status' => $strFeeling ), true ), 'true' ) ? true : false ) ;
    }
    
    public function SetPlaceState( $intID, $boolState )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/build/set-place-state?placeId=' . $intID . '&active=' . strval( $boolState ) , null, true, $this->GetToken( 'CSRF' ) ), 'true' ) ? true : false ) ;
    }
    
    public function SendMessage( $intID, $strSubject, $strBody )
    {
        return ( strpos( $this->NetworkRequest( 'm.roblox.com/messages/sendmessageworks', array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION' ) , 'RecipientId' => $intID, 'Subject' => $strSubject, 'Body' => $strBody ), true ), 'Your message has been sent to' ) ? true : false ) ;
    }
    
    public function GetUnreadMessages( )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'api.roblox.com/incoming-items/counts', null, true ), true ), 'unreadMessageCount' ) ;
    }
    
    public function GetFriendRequestCount( )
    {
        return $this->Is( json_decode( $this->NetworkRequest( 'api.roblox.com/incoming-items/counts', null, true ), true ), 'friendRequestsCount' ) ;
    }
    
    public function RequestFriendship( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/request-friendship', array( 'recipientUserId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function DeclineFriendship( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/request-friendship', array( 'requesterId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function AcceptFriendship( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/request-friendship', array( 'requesterId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function Unfriend( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/unfriend', array( 'friendUserId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function FollowUser( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/follow', array( 'followedUserId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function UnfollowUser( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/user/unfollow', array( 'followedUserId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'Success' ) ? true : false ) ;
    }
    
    public function GetFollowings( $intPage = 1 )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/users/followings?&page=' . $intPage , null, true ), true ) ;
    }
    
    public function BlockUser( )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/userblock/blockuser', array( 'blockeeId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'true' ) ? true : false ) ;
    }
    
    public function UnblockUser( )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/userblock/unblockuser', array( 'blockeeId' => $intID ), true, $this->GetToken( 'CSRF' ) ), 'true' ) ? true : false ) ;
    }
    
    /*
    
        Auth Lib
            /Group
        
    */
    
    public function SetGroupShout( $intID, $strMessage )
    {
		$cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;

		$this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ), '__VIEWSTATE' => $this->GetToken( '__VIEWSTATE', $cache ), '__EVENTARGUMENT' => $this->GetToken( '__EVENTARGUMENT', $cache ), '__EVENTVALIDATION' => $this->GetToken( '__EVENTVALIDATION', $cache ), 'ctl00$cphRoblox$GroupStatusPane$StatusTextBox' => $strMessage, 'ctl00$cphRoblox$GroupStatusPane$StatusSubmitButton' => 'Group Shout' ) , true ) ;
    }
    
    public function SetGroupRole( $intID, $varTarget, $varRole )
    {
        if ( is_int( $varRole ) )
            $this->NetworkRequest( 'www.roblox.com/groups/api/change-member-rank?groupId=' . $intID . '&newRoleSetId=' . $varRole . '&targetUserId=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ), array( ), true, $this->GetToken( 'CSRF' ) ) ;
        else
        {
            $arrTmp = $this->GetGroupPageRoles( $intID ) ;
            if ( isset( $arrTmp[ $varRole ] ) )
                $this->NetworkRequest( 'www.roblox.com/groups/api/change-member-rank?groupId=' . $intID . '&newRoleSetId=' . $arrTmp[ $varRole ] . '&targetUserId=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ) , array( ), true, $this->GetToken( 'CSRF' ) ) ;
        }
    }
    
    public function KickFromGroup( $intID, $varTarget, $boolPosts = false )
    {
        $arrTmp = $this->GetGroupPageRoles( $intID ) ;
        $strTmp = $this->GetRoleInGroup( $intID, $this->GetUserID( $this->currentUser ) ) ;
        
        if ( isset ( $arrTmp[ $strTmp ] ) )
            return $this->NetworkRequest( 'www.roblox.com/my/groups.aspx/exileuseranddeleteposts', json_encode( array( 'userId' => ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ), 'deleteAllPostsOption' => $boolPosts, 'rolesetId' => $arrTmp[ $strTmp ], 'selectedGroupId' => $intID ) ), true, $this->GetToken( 'CSRF' ), 'application/json' ) ;
    }
    
    public function LeaveGroup( $intID )
    {
		$cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;

		$this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ), '__VIEWSTATE' => $this->GetToken( '__VIEWSTATE', $cache ), '__EVENTARGUMENT' => $this->GetToken( '__EVENTARGUMENT', $cache ), '__EVENTVALIDATION' => $this->GetToken( '__EVENTVALIDATION', $cache ), 'ctl00$cphRoblox$GroupStatusPane$StatusSubmitButton' => 'Leave Group' ) , true ) ;
    }
    
    public function JoinGroup( $intID )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/Groups/Groups.aspx?gid=' . $intID, null, true ) ;

		$this->NetworkRequest( 'www.roblox.com/Groups/Groups.aspx?gid=' . $intID, array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ), '__VIEWSTATE' => $this->GetToken( '__VIEWSTATE', $cache ), '__EVENTARGUMENT' => 'Click', '__EVENTVALIDATION' => $this->GetToken( '__EVENTVALIDATION', $cache ), '__EVENTTARGET' => 'JoinGroupDiv' ) , true ) ;
    }
    
    public function MakePrimary( $intID )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;

		$this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, array( '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ), '__VIEWSTATE' => $this->GetToken( '__VIEWSTATE', $cache ), '__EVENTARGUMENT' => $this->GetToken( '__EVENTARGUMENT', $cache ), '__EVENTVALIDATION' => $this->GetToken( '__EVENTVALIDATION', $cache ), 'ctl00$cphRoblox$GroupStatusPane$StatusSubmitButton' => 'Make Primary' ) , true ) ;
    }
    
    public function InviteToClan( $intID, $varTarget )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;
        
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/group/invite-to-clan', array( 'userIdToInvite' => ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ), 'groupId' => $intID, '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ) ), true, $this->GetToken( 'CSRF', $cache ) ), 'true' ) ? true : false ) ;
    }
    
    public function CancelClanInvite( $intID, $varTarget )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;
        
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/group/cancel-invitation', array( 'inviteeUserId' => ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ), 'groupId' => $intID, '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ) ), true, $this->GetToken( 'CSRF', $cache ) ), 'true' ) ? true : false ) ;
    }
    
    public function AcceptClanInvite( $intID, $boolAccepting )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;
        
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/group/accept-decline-clan-invitation', array( 'isAccepting' => ( $boolAccepting === true ? 'true' : 'false' ), 'groupId' => $intID, '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ) ), true, $this->GetToken( 'CSRF', $cache ) ), 'true' ) ? true : false ) ;
    }
    
    public function KickFromClan( $intID, $varTarget )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;
        
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/group/kick-from-clan', array( 'userIdToKick' => ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ), 'groupId' => $intID, '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ) ), true, $this->GetToken( 'CSRF', $cache ) ), 'true' ) ? true : false ) ;
    }
    
    public function LeaveClan( $intID )
    {
        $cache = $this->NetworkRequest( 'www.roblox.com/My/Groups.aspx?gid=' . $intID, null, true ) ;
        
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/group/leave-clan', array( 'groupId' => $intID, '__RequestVerificationToken' => $this->GetToken( 'VERIFICATION', $cache ) ), true, $this->GetToken( 'CSRF', $cache ) ), 'true' ) ? true : false ) ;
    }
    
    /*
    
        Auth Lib
            /Asset
        
    */
    
    public function GetAsset( $intID )
    {
        return json_decode( $this->NetworkRequest( 'api.roblox.com/Marketplace/ProductInfo?assetId=' . $intID  ), true ) ;
    }
    
    public function BuyAsset( )
    {
        // TBA
    }
    
    public function HasAsset( $varTarget, $intID )
    {
        return ( strpos( $this->NetworkRequest( 'api.roblox.com/Ownership/HasAsset?userId=' . ( is_string( $varTarget ) ? $this->GetUserID( $varTarget ) : $varTarget ) . '&assetId=' . $intID , array( ), true ), 'true' ) ? true : false ) ;
    }
    
    public function UpdateAsset( $intID, $strName, $srtDesc, $boolComments, $intGenre, $boolForSale, $intRobux = 0, $intTickets = 0 )
    {
        $cache = $this->NetworkRequest( 'http://www.roblox.com/My/Item.aspx?ID=' . $intID, null, true ) ;

		if ( ( $intRobux <= 0  ) or ( $intTickets <= 0 ) )
			$this->NetworkRequest( 'http://www.roblox.com/My/Item.aspx?ID=' . $intID, array( '__EVENTTARGET' => 'ctl00$cphRoblox$SubmitButtonTop', '__EVENTARGUMENT' => '', '__VIEWSTATE' => $this->GetToken( 'VIEWSTATE', $cache ), '__EVENTVALIDATION' => $this->GetToken( 'EVENTVALIDATION', $cache ), 'ctl00$cphRoblox$NameTextBox' => $strName, 'ctl00$cphRoblox$DescriptionTextBox' => $srtDesc, 'ctl00$cphRoblox$EnableCommentsCheckBox' => ( $boolComments === true ? 'on' : '' ), 'GenreButtons2' => $this->GetGenreSetting( $intGenre ), 'ctl00$cphRoblox$actualGenreSelection' => $this->GetGenreSetting( $intGenre ), 'ctl00$cphRoblox$PublicDomainCheckBox' => ( $boolForSale === true ? 'on' : '' ) ), true ) ;
		else
			$this->NetworkRequest( 'http://www.roblox.com/My/Item.aspx?ID=' . $intID, array( '__EVENTTARGET' => 'ctl00$cphRoblox$SubmitButtonTop', '__EVENTARGUMENT' => '', '__VIEWSTATE' => $this->GetToken( 'VIEWSTATE', $cache ), '__EVENTVALIDATION' => $this->GetToken( 'EVENTVALIDATION', $cache ), 'ctl00$cphRoblox$NameTextBox' => $strName, 'ctl00$cphRoblox$DescriptionTextBox' => $srtDesc, 'ctl00$cphRoblox$EnableCommentsCheckBox' => ( $boolComments === true ? 'on' : '' ), 'GenreButtons2' => $this->GetGenreSetting( $intGenre ), 'ctl00$cphRoblox$actualGenreSelection' => $this->GetGenreSetting( $intGenre ), 'ctl00$cphRoblox$PublicDomainCheckBox' => ( $boolForSale === true ? 'on' : '' ), 'SellForRobux' => ( $intRobux > 0 ? 'on' : '' ), 'SellForTickets' => ( $intRobux > 0 ? 'on' : '' ), 'RobuxPrice' => $intRobux, 'TicketsPrice' => $intTickets ), true ) ;
	
    }
    
    public function ToggleFavoriteAsset( $intID )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/favorite/toggle' , array( 'assetId' => $intID ), true ), 'true' ) ? true : false ) ;
    } 
    
    public function IsAssetFavorited( )
    {
        // TBA  
    }
     
    public function VoteAsset( $intID, $boolVote )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/voting/vote?assetId=' . $intID . '&vote=' . ( $boolVote ? 'true' : 'false' ) , array( ), true ), 'true' ) ? true : false ) ;
    }   
    
    /*
    
        Auth Lib
            /Misc
        
    */
    
    public function RedeemPromocode( $strCode )
    {
        return ( strpos( $this->NetworkRequest( 'www.roblox.com/promocodes/redeem?code=' . $strCode, array( ), true ), 'true' ) ? true : false ) ;
    }
    
    /*
    
        Auth Lib
            /Chat
        
    */
}
