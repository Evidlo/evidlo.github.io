<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>

<!-- Index Template -->

<xsl:template match="index">
<html>
    <head>
        <title>
            <xsl:value-of select="title"/>
        </title>
        <link rel="stylesheet" type="text/css" href="/styles.css" />
    </head>

    <body>
        <main>
            <h1><xsl:value-of select="title"/></h1>
            <xsl:apply-templates select="section"/>
        </main>
    </body>

    <footer>
        <p>Evan Widloski</p>
    </footer>
</html>
</xsl:template>

<!-- Section Template -->

<xsl:template match="section">
    <article>
        <h2>
            <xsl:value-of select="title"/>
        </h2>
        <xsl:apply-templates select="entry"/>
    </article>
</xsl:template>

<!-- Entry Template -->

<xsl:template match="entry">
    <li>
        <a href="{link}"><xsl:value-of select="title"/></a>
    </li>
</xsl:template>

<!-- Page Template -->

<xsl:template match="page">
<html>
    <head>
        <title>
            <xsl:value-of select="title"/>
        </title>
        <link rel="stylesheet" type="text/css" href="/styles.css" />
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

<!-- Wildcard Template -->

<xsl:template match="node()|@*">
    <xsl:copy>
        <xsl:apply-templates select="node() | @*"/>
    </xsl:copy>
</xsl:template>

</xsl:stylesheet>
