# AmbiverseNLU Demo

AmbiverseNLU WordPress plugin for the demo on [https://ambiversenlu.mpi-inf.mpg.de](https://ambiversenlu.mpi-inf.mpg.de). 

For the example sentence below:

```
Jack founded Alibaba with investments from SoftBank and Goldman.
```

The AmbiverseNLU Demo will produce the following outputs:

[![AmbiverseNLU Demo](images/demo.png "AmbiverseNLU Demo")](http://ambiversenlu.mpi-inf.mpg.de)

## Installing

To install the demo on a WordPress instance, you need to package the code in a `.zip` archive, and then `Add` and `Activate` the plugin.

## Configuration

To be able to use the demo, you need to configure it to consume the [AmbiverseNLU](https://github.com/ambiverse-nlu/ambiverse-nlu) Web Service.
This code uses the public Web Service API running on [https://api.ambiverse.com](https://api.ambiverse.com). 
To do the configuration, go to, `Settings`->`Ambiverse Entity Linking Demo`. The screenshot below shows an example configuration. 
Before you can use it, you need to obtain OAuth2 credentials from [https://developer.ambiverse.com](https://developer.ambiverse.com).

![AmbiverseNLU Demo Configuration](images/demo-config.png "AmbiverseNLU Demo Config")

## Usage

To use the demo in a WordPress page or a post you add the short code displayed in the configuration page. For example, you can add this.

~~~~~
[ambiverse-eld coherent-document="true" confidence-threshold=0.075 concept="true" language="en" settings-api-endpoint="api" settings-api-method="/entitylinking/"]When [[Who]] played Tommy in Columbus, Pete was at his best.[/ambiverse-eld
~~~~~

## Further Information

* AmbiverseNLU project website: [http://www.mpi-inf.mpg.de/ambiverse-nlu/](http://www.mpi-inf.mpg.de/ambiverse-nlu/)
* AmbiverseNLU project on GitHub: [https://github.com/ambiverse-nlu/ambiverse-nlu](https://github.com/ambiverse-nlu/ambiverse-nlu)

## AmbiverseNLU Demo License

[Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0.html)