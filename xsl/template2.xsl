<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>

<xsl:template match="page">
<html>
<head>
    <title>
        <xsl:value-of select="title"/>
        <link rel="stylesheet" type="text/css" href="/styles.css" />
    </title>
</head>
<body>
    <main>
        <article>
            <header>
                <h1><xsl:value-of select="title"/></h1>
                <time datetime="2020">2020</time>
            </header>
            <xsl:apply-templates select="content"/>
        </article>
    </main>
</body>
<footer>
    <p>Evan Widloski</p>
</footer>
</html>
</xsl:template>

<xsl:template match="node()|@*">
    <xsl:copy>
        <xsl:apply-templates select="node() | @*"/>
    </xsl:copy>
</xsl:template>

</xsl:stylesheet>
