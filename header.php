<?php
if (isset($_POST['logout'])) {
	session_destroy();
	header("Location:home");
}
echo "
	<header>
        <div class='container'>
            <div class='row'>
                <div class='col-6'>
					<div id='top-bar'>
						<div id='header-logo'>
							<a href='home'>
								<img id='platyweb-logo' src='./img/platyweb.svg'/>
							</a>
						</div>
					"./* Logout Button
					<form method='post'>
						<button type='submit' name='logout' id='header-logout-button'>Ausloggen?</button>
					</form>*/
	    		    "<div class='header-icons'>
						<a href='shoppingCart'>
	    			    	<img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSdVpMdS6yhVyKICX1nqw6z83TYQpwBD6C2B-A91gpqww&s' height='50' alt='Warenkorb'>
						</a>
						<a href='kontoPage'>
	    			    	<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH4AAAB+CAMAAADV/VW6AAAAZlBMVEX///8AAAD8/PxbW1vw8PD19fW9vb2ysrKkpKQ9PT3i4uJkZGT4+PhpaWmHh4epqanU1NTc3NyBgYETExMuLi7Dw8MgICBubm5ISEjp6ekmJiaXl5dCQkJ3d3fKysqOjo41NTVQUFDKOy6BAAAFAklEQVRoge1b6ZayOhBUtggom7IJOvL+L/mpQycBCWTpzJx77tRfQwqSXitxt/vDfxPOG7/B3JLu1O/f6E8diX6S+55U+xmq5P4z3F56mXN/45K21sn9TkD+foHOt8seXMXk7z0ILJJ78Tr5C65ni738MLjFBSjtsB/mRNdwcN0h/NiPgw32bkJxdtN79F5nL8qIe5782FlmD8lshz3SW+Xn2YdsaUQ22OMnnGkR4SDONIWDdJCzed2V8B65bFyOSB/SWYvVBOcUzDzw2NnGJ1tDE/ztj+iUX9uDv+hgrCR8gglrmdG10uhtZHQ7pcK5Rw1l0T+VEStOR183xmCnTrdpdgBq/hjOR83+KPvEEdP4IaFJWD0ArP9qzk7XXsGPqKearz5k+UHloWZ8KDWmB6dXKuKC8aGTMf1YSJyVYlgET5mye+N3NEq9lAOrb1p35nrLCFtmanuwi9Ix5xvgeqZlf6pnw5qPfeCg9xmwaKY1N9Ar9g4lMv0vfX2qNw+8tenewyoqWj6UfKb9Hvi9YukAJYqp30PU66Wz/QtHaLmM5YZK5ztgzSpT9t1Nx4jAYG/G9DBTr/JQr/POi9CpXHQqJBFAySrkH4FS92LOzjQV6dwN3oKisrQgnEg3TZDszygyI9i+bAiDQIlg9y9QQ7pIfU1LZU8khYF+visz2sX9+Of3wIQyFR/txvdoAjMTljbNj3b3mOISk4zqVffzGLvURkmiZaplv2JQeUOH4Tgd4L5nELbNvPSIfLbB6Yr7M1loeRzC67qoquKcf3/pZluQTw850Nln/M+Guwii1vN9r42CZJj+ZoGdFc/8KvT9wtGOpQOVfOUUiXslTDV3Audrmz2xeaqZNevkDY6UKIQThGLycMkjsV+gFKxAX/7QaXJ7+DjQiw/2T1A5OFla1G7zhFsXaabUA2Hh6D/xK8z/P+SBhFdngZWo55Svxi0M1qud4D0I3QOdYBg97FFkgqbdz5LHOGgIUF9gckC6D4ugnU3veEExCYcDXrnj3fYfqOLkQMrsiZIcknjhZP+GdI8g/ZxaDubd/XNHJS5MiBAbSzuZVIkhwsUw/5JtinUYlX0FP1NFnPIkogGcAodM7FBBFJljsu3f3YVPauF2XGvyvdkdX/LrnmUe+U+tmUrk5Ye4mV1XuTZxlzNPi2rut5NWTjzyU8y38BhlAemK2xNFR4IsmlPwRlPr8HPsoUYl03JBUOMonYt0mjJFYTADd0VJWx7Tn4NrqA08lzMApQzkVRjsPH+lkoCYwxumDZauFNyfbZqxLKoxVUSDluIJzhLoRR7pQ2iqY7kIJZMz0NnkHqCarPH58xtU35bUhGlKQVIpqCoiJe9TY0W6dcSFbwk38iFWX9Fa1hbSY7hdfNFIgXjhk3rfdgwD7SBE7FyPsKLN1kga7FHVMWp9W6Efwi3iZcMX4PM3DgSodI0sTIJFbQjd1PBw2Xc7OeMDw0MI9lNA6F+/ewUvia4OZjLLChaqdF4sBzC+NY+CJVK4mycLkINXtpVetbKgzMLqD+Ik7o9DHhb+YeCB8CKO+/CGKFdM54g3VxZyrYWtZ5svzrrQl1j5dwUUUeKOG+oCK+I0nAQLqxh/GEdY+W8L1HyDyPbgdvVmVtbD6NXCm96Q7irXCsbGTZj02D18qxC1G3/0v0jvrxzR4UFc7C8cEuNjJeHfH9uPm+GxXmo7lrFK/ocfxT+B6TUhZyxIRwAAAABJRU5ErkJggg==' height='50' alt='Anmelden'>
						</a>	
					</div>    
	    	    </div>
            </div>
            <div class='row'>
                <div class='col-6'>
					<a href='home'>
	    	        	<div id='banner'>
	    		        	<img src='../img/banner.png' alt='Banner'>
	    	        	</div>
					</a>
                </div
            </div>
            <div class='row'>
                <div class='col-6'>
	    	        <div id='bottom-bar'>
	    		        <div class='search-box'>
	    			        <input type='text' placeholder='Was suchst du?'>
	    		        </div>
	    	        </div>
                </div>    
            </div>
        </div>
	</header>
    ";