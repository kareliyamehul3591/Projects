import React from 'react';
import {GoogleLogout } from 'react-google-login';


const clientId = "479275290890-v81dfl997kin3730kliargstac4ptt4g.apps.googleusercontent.com";

function LogoutByGoogle() {
    
    const onSignoutSuccess = () => {
        localStorage.setItem('isLogin', 0);
        sessionStorage.removeItem('googledata');
       
        };


    return (
        <div id="signoutgoogle">
                          
                <GoogleLogout
                    clientId={clientId}
                    className="logout"
                    buttonText="Sign Out"
                    onLogoutSuccess={onSignoutSuccess}
                >
                </GoogleLogout> 
            
        </div>
    );
}
export default LogoutByGoogle;