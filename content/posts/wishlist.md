---
title: Technology Wishlist
description: Things I wish were different in the world of technology
date: 2021-01-18
---

# Better name resolution on home networks

Home routers suck.  I want to be able to connect from one device to another on my network without manually looking up its IP address or having the address change when the device reboots.

If you Google "local hostname resolution on home router", you usually get these two suggestions:

1. Add the ip addresses to your computer's /etc/hosts file.

2. Use mdns/avahi/bonjour.

1 is a non-answer, really.  It still requires you to look up the address manually initially, and if the ip address changes you have to go back and manually update this file.  Also you have to do this on each device on your network for each remote service you want to connect to.  No good.

2 mDNS (multicast DNS) works by sending out a broadcast to all devices on the local network, which then respond with their address/hostname and a list of services they support.  This seems great in principle, except that it requires all clients to a program which listens and responds to these requests.  While even the most basic IoT doodad will support regular DNS on their network stack, they almost surely won't support mDNS.  It's not even supported on Android.

In my opinion, the idiomatic solution is for home routers to run a local DNS server with new entries added based on client device hostnames when the client connects to the network.

This is actually what ddwrt-flashed routers do by default.

# CSV derivative with metadata support

When I was working on the [Rapidalarm](https://rapidalarm.github.io/#!index.md) project during the first Summer of COVID, I found myself needing to share recordings on ventilator data with a team that included technical and non-technical people.  Some of these people used Matlab while I was using a Python/Numpy pipeline for data processing.  Others with less technical backgrounds also needed to be able to plot/manipulate this data.

In order of importance, the requirements for this data format would be

- Excel importability
- Embedded metadata support for recording test conditions, sample rate, etc.
- human readabiliity

| Format | Excel importable | Metadata | Human readable |
|--------|------------------|----------|----------------|
| CSV    | Yes              | No       | Yes            |
| HDF5   | No               | Yes      | No             |
| MAT    | No               | Yes      | No             |
| XLS    | Yes              | Yes      | No             |
| NPY    | No               | Yes      | No             |

*edit* I've found [CSVY](https://csvy.org) which is CSV with metadata embedded as YAML frontmatter at the top of the file.  It can also support embedding schema information there as well.

<!--
# Browser support for SRV records
# Common theming for static HTML website
- html imports
  - requires javascript?
- XSL (kinda) - github.com/evidlo/xsl-website
  - requires writing ugly XSLT
  - requires authoring content in XML
- client side generation
  - mdwiki
  - docsify
-->
