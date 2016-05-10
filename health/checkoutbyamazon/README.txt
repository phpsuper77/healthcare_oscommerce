*-*-**-***-*****-********-*************
CheckoutByAmazonService Java Library
Copyright 2011 Amazon.com, Inc. or its affiliates. All Rights Reserved.
Licensed under the Apache License, Version 2.0 (the "License"); 
*-*-**-***-*****-********-*************
  
*-*-**-***-*****-********-*************
INTRODUCTION
*-*-**-***-*****-********-*************
 
 Please understand that by using the CheckoutByAmazonService sample code, 
 you are agreeing to understand and abide by the terms of the license, 
 as written in NOTICE.txt & LICENSE.txt accompanying this archive. 
  
*-*-**-***-*****-********-*************
INCLUDED FILES
*-*-**-***-*****-********-*************
 
 * lib/                          - Directory containing all jars that are required to run this sample code.
 * src/                          - Java sample files which shows how to use the CheckoutByAmazonService
 * LICENSE.txt                   - Apache License this code is licensed under.
 * NOTICE.txt                    - Notice file.
 * README.txt                    - This file.
 
*-*-**-***-*****-********-*************
USAGE INSTRUCTIONS 
*-*-**-***-*****-********-*************
 Note: The following steps are for a UNIX based operating environment (and can be easily modified to suite other operating systems)
 
 (1) In order to execute the sample code, copy the CheckoutByAmazonService.properties downloaded from the code generator in to the the src directory or to the root directory of the server.
      
 (2) Edit the sample file  you want to execute. 
     For example: Edit src/com.amazon/samples/OrderWithItemCharges.java to try out a sample checkout operation
                a) Add the purchase contract Id which you have obtained by using Inline checkout widgets at the locations where we have specified as <ADD PURCHASE 
                CONTRACT ID HERE>. You can modify the code so as to pass the Purchase Contract Id as a get or post parameter and use it.
 (3) Edit the sample code by modifying the various item parameters like Item Title, Price etc.

 		    
 (3) Set appropriate environment variable 
        (a) Set JAVA_HOME to point to the directory location of a valid JDK folder (this sample code has been tested with JDK 1.6)
        (b) Set PATH variable to $JAVA_HOME/bin (bin directory of JDK) 
 
 (4) Navigate to src directory (src directory inside the directory where this README file is located)
 
 (5) compile the java source file you have edited (on a linux bash shell)
     Ex:
  	 javac -classpath
         ./lib/amazon/CBAInlineCheckoutService.jar:./lib/third-party/commons-io-1.1/commons-io-1.1.jar:./lib/third-party/commons-codec-1.3/commons-codec-1.3.jar:./lib/third-party/commons-httpclient-3.0.1/commons-httpclient-3.0.1.jar:./lib/third-party/jaxb-ri-2.1/activation.jar:./lib/third-party/commons-logging-1.1/commons-logging-1.1.jar:./lib/third-party/log4j-1.2.14/log4j-1.2.14.jar:./lib/third-party/jaxb-ri-2.1/jaxb-api.jar:./lib/third-party/jaxb-ri-2.1/jaxb-impl.jar:./lib/third-party/jaxb-ri-2.1/jaxb-xjc.jar:./lib/third-party/jaxb-ri-2.1/jsr173_1.0_api.jar:./src/com/amazon/samples
         src/com/amazon/samples/*.java
 (6) run the java file
     Ex:
 	  java -classpath
          ./lib/amazon/CBAInlineCheckoutService.jar:./lib/third-party/commons-io-1.1/commons-io-1.1.jar:./lib/third-party/commons-codec-1.3/commons-codec-1.3.jar:./lib/third-party/commons-httpclient-3.0.1/commons-httpclient-3.0.1.jar:./lib/third-party/jaxb-ri-2.1/activation.jar:./lib/third-party/commons-logging-1.1/commons-logging-1.1.jar:./lib/third-party/log4j-1.2.14/log4j-1.2.14.jar:./lib/third-party/jaxb-ri-2.1/jaxb-api.jar:./lib/third-party/jaxb-ri-2.1/jaxb-impl.jar:./lib/third-party/jaxb-ri-2.1/jaxb-xjc.jar:./lib/third-party/jaxb-ri-2.1/jsr173_1.0_api.jar:./src
          com.amazon.samples.OrderWithItemCharges

Notes:
  
  Truststore:
  
  (1) You will need a trust store with authentic and valid VeriSign certificate to be able to connect to the CheckoutByAmazonService over SSL. 
      JDK comes with a utility keytool ($JAVA_HOME/bin/keytool) that can be used to import certificates into your java key store (.jks file)
      
*-*-**-***-*****-********-*************
SUPPORT & PROJECT HOME
*-*-**-***-*****-********-*************
 The latest documentation on the CheckoutByAmazonService can be found at the LINKS section below.
         
*-*-**-***-*****-********-*************
LINKS
*-*-**-***-*****-********-*************

CheckoutByAmazonService Documentation:
---------
https://payments.amazon.co.uk/business/resources#cba
