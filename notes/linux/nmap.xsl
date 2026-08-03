<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="text" encoding="UTF-8"/>

  <xsl:template match="/nmaprun">
    <xsl:for-each select="host">
      <!-- <xsl:text>===== </xsl:text> -->

      <!-- Host IP Uptim -->
      <xsl:value-of select="address[@addrtype='ipv4']/@addr"/>
      <xsl:if test="hostnames/hostname/@name">
        <xsl:text> (</xsl:text>
        <xsl:value-of select="hostnames/hostname/@name"/>
        <xsl:text>)</xsl:text>
      </xsl:if>
      <xsl:text> </xsl:text>
      <xsl:value-of select="status/@state"/>
      <xsl:text> </xsl:text>
      <xsl:value-of select="times/@srtt"/>
      <xsl:text> μs</xsl:text>
      <!-- <xsl:text> =====&#xa;</xsl:text> -->
      <xsl:text>&#xa;</xsl:text>

      <!-- MAC address-->
      <xsl:if test="address[@addrtype='mac']">
        <xsl:value-of select="address[@addrtype='mac']/@addr"/>
        <xsl:if test="address[@addrtype='mac']/@vendor">
          <xsl:text> - </xsl:text>
          <xsl:value-of select="address[@addrtype='mac']/@vendor"/>
        </xsl:if>
        <xsl:text>&#xa;</xsl:text>
      </xsl:if>

      <!-- Ports header -->
      <xsl:if test="ports/port">
        <xsl:for-each select="ports/port">
          <xsl:variable name="port_proto" select="concat(@portid, '/', @protocol)"/>
          <xsl:value-of select="$port_proto"/>
          <xsl:call-template name="pad">
            <xsl:with-param name="input" select="$port_proto"/>
            <xsl:with-param name="length" select="9"/>
          </xsl:call-template>
          <xsl:value-of select="state/@state"/>
          <xsl:call-template name="pad">
            <xsl:with-param name="input" select="state/@state"/>
            <xsl:with-param name="length" select="10"/>
          </xsl:call-template>
          <xsl:value-of select="service/@name"/>
          <xsl:text>
</xsl:text>
        </xsl:for-each>
      </xsl:if>


      <xsl:text>
</xsl:text>
    </xsl:for-each>
  </xsl:template>

  <!-- Padding for alignment -->
  <xsl:template name="pad">
    <xsl:param name="input"/>
    <xsl:param name="length"/>
    <xsl:variable name="padlen" select="$length - string-length($input)"/>
    <xsl:if test="$padlen &gt; 0">
      <xsl:value-of select="substring('          ', 1, $padlen)"/>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>