<?php include("../../header.html");?>

<body>
	<div class="v h">
		<div class="h hc">
			<div class="simplebox">
				<div class="title">Doorbot</div>
				<div class="body v">
          <div class="project_head h">
            <a  href="http://github.com/evidlo/DAS">
            <div class="v vc">
                <div class="h">
                      <div><img src="/static/github.png"/></div>
                      <div class="v vc">Project Files</div>
                </div>
            </div>
            </a>
        </div>
					<div class="annotate">
						<img src="mhacks.png"/>
            MHacks 2013
						<img src="demo.png"/>
            Hackathon demonstration board.
					</div>
          <p>  This is a dorm automation system (DAS) I built at a hackathon with my freshman year roommate.  It features a remotely controllable door unlocker and relays for controlling lights.  We built a web interface for controlling the DAS remotely and viewing the state of the door while we're away.  The system also featured a piezoelectric sensor which would listen for a secret knock to open the door.  In the code, we normalized the knock based on the length of the first pulse so that the secret knock can be recognized regardless of the speed.
            <p> Later on, I moved the boards into a gutted DSL modem.  I left the ports intact and soldered my boards to them.
        <div class="annotate">
					    <iframe width="560" height="315" src="https://www.youtube.com/embed/Y7vDsM4oP3w" frameborder="0" allowfullscreen></iframe>
              </div>
        <div class="annotate">
          <img src="door.jpg">
          The door unlocker
        </div>
        <p> The door unlocker consists of a servo mounted over the deadbolt of the door and a sensing switch to detect when the door opens or closes.  Initially I was having issues with the long wire that connected the servo to the control circuitry.  The servo would pull lots of current and the voltage would plummet due to the impedance of the wire (a few ohms).  I remedied this by stepping up the voltage to 12V on the wire and then regulating it back down to 5V near the servo.  This is the same reason powerlines are usually at several kV which is then stepped down to the residential 110VAC.
        <div class="annotate">
						<img src="modem1.jpg"/>
						<img src="modem2.jpg"/>
            Repurposed DSL modem
					</div>
        <div class="annotate">
          <img src="interface.png"/>
          <img src="log.png"/>
          The control and log interfaces.
        </div>
        
					<div class="annotate">
						<img src="a.png"/>
						<img src="b.png"/>
						<img src="c.png"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
