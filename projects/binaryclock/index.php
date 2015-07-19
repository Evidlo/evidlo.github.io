<?php include("../../header.html");?>

<body>
	<div class="v h">
		<div class="h hc">
			<div class="simplebox">
				<div class="title">Binary Clock</div>
				<div class="body v">
					<div class="annotate">
						<img src="lightfinal.jpg"/>
						Clock showing 7:45.
					</div>
					<p> This is a binary clock that I threw together in a few days almost entirely with parts on hand.  Each digit that you would normally see on a digital clock is represented as columns of 4 bits, with the least significant bit on the top row.
					<div class="annotate">
						<img src="display.jpg"/>
						<img src="display2.jpg"/>
						Display panel	
					</div>
					<p>
					I didn't want to spend any money on this project, so everything is made out of scrap balsa and acrylic.  The first piece I made was the display.  I sandwiched a few pieces of balsa together, drilled some holes and glued a piece of acrylic over the front to diffuse the LEDs.  I also glued in a white sheet of paper and sanded the front of the acrylic to give it a frosty look.
					<div class="annotate">
						<img src="build1.jpg"/>
						<img src="build2.jpg"/>
						Display panel	
					</div>
					<p> For driving the LEDs, I used the ubiquitous HC595 shift register.  I cut apart some small perfboard and soldered in an IC socket so the chips can easily be replaced if they burn out.  If you notice that the resistors are all different values, it's to make the brightnesses of all the colors match.
					<div class="annotate">
						<img src="tiny.jpg"/>
						Microcontroller.
					</div>
					<p> Next, I soldered up an attiny (again with IC socket) and added headers for the realtime clock (RTC) module, which communicates with the tiny via I2C. To set the time, I wrote some code that sets the time immediately on boot with some hardcoded value.  Then I just waited until that time came and plugged the clock in.  I only have to do this once, as the RTC module uses the battery to keep the time even when power is lost.
					<div class="annotate">
						<img src="case.jpg"/>
						Watching glue dry is my favorite thing.
					</div>
					The last step was to glue the back on and add a hinged lid so I can reprogram the device if necessary.
					<img src="darkfinal.jpg"/>
					<img src="displaydark.jpg"/>
					This thing really gives off a nice glow at night.  I'm pleased with the results.
					<p> <a href="http://github.com/evidlo/binary_clock">Github Page</a>
				</div>
			</div>
		</div>
	</div>
</body>
