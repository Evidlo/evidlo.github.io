<?php include("../../header.html");?>

<body>
	<div class="v h">
		<div class="h hc">
			<div class="simplebox">
				<div class="title">Powerline Transmission Prototype</div>
				<div class="body v">
          <div class="project_head h">
            <a  href="http://github.com/evidlo/transmission">
            <div class="v vc">
                <div class="h">
                      <div><img src="/static/github.png"/></div>
                      <div class="v vc">Project Files</div>
                </div>
            </div>
            </a>
        </div>
					<div class="annotate">
						<img src="receiver_board.out"/>
						<img src="transmitter_board.out"/>
            Transmitter and Receiver Prototypes
					</div>
					<p>
          Above are some prototype PCBs for a powerline transmission scheme I was helping develop on the Purdue IEEE ROV team.  A common problem on ROVs is the drag caused by pulling the tether through the water.  This tether contains channels for powering and communicating with the vehicle, and when systems like pneumatics or hydraulics are needed it begins to resemble an umbilical cord rather than a sleek cable.
          <p>
          One solution to this problem is to reduce the electrical cable thickness by reducing the number of channels.  This can be achieved by using <a href="https://en.wikipedia.org/wiki/Power-line_communication">powerline transmission</a>.  The basic idea is to take all your data channels and shift them to non-overlapping frequencies, add them together and inject them into the power cable.  On the receiving end, the data is separated from the power and split into its original components through the use of various filters.
					<div class="annotate">
						<img src="modulator.out"/>
            Data modulator.  Shifts data to a different frequency.
					</div>
					<div class="annotate">
						<img src="transmitter.out"/>
            Summing amplifier.  Combines video and data streams and injects into the powerline.
					</div>
					<p>
            The boards utilize a communication scheme called <a href='https://en.wikipedia.org/wiki/Amplitude-shift_keying'>amplitude shift keying</a> (ASK) wherein a digital signal is modulated with a high frequency sine wave for the purpose of transmission.  Below is a SPICE simulation of what the data looks like after leaving the summing amplifier.
					</p>
					<div class="annotate">
						<img src="spice_trans.out"/>
            Transmission simulation.
					</div>
          <p>
            The next step is to receive the data and return it to a usable form.  For this we use a series of filters to eliminate unwanted signals and leave only the desired output.  Below, you can see circuit to extract the data stream and also some photos of the results. Don't pay too much attention to the schematic component values.
					<div class="annotate">
						<img src="receiver.out"/>
            Receiving filter.  In order: input buffer, two 2nd-order lowpass filters, half wave rectifier (super diode), output comparator.
					</div>
					<div class="annotate">
						<img src="video.out"/>
            Video being transmitted over powerline. A bit grainy.
					</div>
					<div class="annotate">
						<img src="scope.out"/>
            Receiving the datastream at 400kbit/s
					</div>
          Overall, the prototype was a success and we were able to transmit and recover both streams over the powerline.  However, this system needs more fine tuning before it can be usable in the field.
				</div>
			</div>
		</div>
	</div>
</body>
