<?php include("../../header.html");?>

<body>
	<div class="v h">
		<div class="h hc">
			<div class="simplebox">
				<div class="title">Purdue IEEE Motor Controllers</div>
				<div class="body v">
            <a class="project_head" href="http://github.com/evidlo/motrocontroller">
            <div class="v vc">
                <div class="h">
                      <div><img src="/static/github.png"/></div>
                      <div class="v vc"> Project Files</div>
                </div>
            </div>
            </a>
					Shown below is a motor controller for the Seabotix BD-166 thruster.  These thrusters are designed to go on ROVs and can withstand depths of several hundred feet.  The BD-166 in particular keeps it's motor controller inside of the thruster itself which reduces wiring and saves space in the main electronics compartment.<br><br>
					<div class="annotate">
						<img src="seabotix.jpg"/>
						The original burned out unit.
					</div>
					<p>
					At some point, there was a communication disconnect with Seabotix and somebody on the ROV team the previous year had powered these off 50VDC, burning a nice hole through our very expensive boards.  'Twas a very bad day.<br><br> 

					Our options were
					<ul>
					<li>a) throw more money at these thrusters which already cost several hundred dollars to replace the boards</li>
					<li>b) switch to a different brand of thrusters and take a loss on these</li>
					<li>c) design a new motor controller to replace the original units</li>
					</ul>

					`a` was out since it would cost another few thousand to get all the drivers replaced.  At the time, there were no good alternatives to the Seabotix thrusters in this niche market, so `b` was out, too (note: there exist some <a href="http://www.bluerobotics.com/">decent alternatives</a> now).
					Eventually I went with option `c`, designing my own motor controllers to replace the units.
					</p>

					<div class="annotate">
						<img src="boards.png"/>
						Motor controller evolution. (note that the 2nd v4 should actually be v5)
					</div>
					<p>
					Here are the 5 iterations of the board showing layout progression as well as a few parts changing.  About halfway through, I realized my boards needed to have a screwhole to keep the board from bumping around in the thruster case.  Another big issue I had was the layout for the power mosfets (the second row of components from the top).
					<br><br>If you notice that the pads change slightly between V5 and V6, this is because I was using a part from the Eagle library that was every so slightly incorrect.  This made the boards very unreliable and was a major headache until I figured out what the problem was.
					</p>
					<div class="annotate">
						<img src="mc_test.jpg"/>
						Probing some comm signals on a V2.
					</div>
					<div class="annotate">
						<img src="mc_in_thruster.jpg"/>
						A V5 being tested with a thruster.
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
