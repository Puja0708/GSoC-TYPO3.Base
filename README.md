GSoC-TYPO3.Base
===============
This is the standalone version of the Fluid template rendering project. 

The TYPO3.Base package is the package which is independent of Flow but contains almost all the functionalities of the Fluid package.

This package was created with the idea, that it might be very difficult to make "Fluid" a 100% standalone. So, the main Fluid package is
divided into two packages :
1. TYPO3.Base (the name can be changed) : contains the base of the Fluid package, without the dependencies.
2. TYPO3.Fluid : acts as a linker of the rendering package to the 'Flow'. It extends the classes that normally should have a dependency from the Base package and then adds those dependencies.

In short, everything that doesn't need Flow to function is contained in the Base package, and everything that needs a dependency on Flow is added to the TYPO3.Fluid package.


Current status :

TYPO3.Fluid and TYPO3.Base packages are ready. 
Some work needs to be done, to get the entire package up and running.

For more details, refer to:
https://docs.google.com/document/d/1VPAKTWDkmVduLMQcTM9-gZdRE_dP7WNODmyFphI1tSE/
