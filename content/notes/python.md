# Python

Simple guide on creating Python packages.

## Creating a Package

For a package called `foobar`, create the following file structure:

```
foobar/
├── setup.py
├── README.md
└── foobar/
    ├── __init__.py
    ├── version.py
    └── foobar.py
```

__init__.py

```
Empty file
```

version.py

```
__version__="0.0.1"
```

setup.py

``` python
from setuptools import find_packages, setup

with open("README.md") as f:
    README = f.read()

version = {}
# manually read version from file
with open("foobar/version.py") as file:
    exec(file.read(), version)

setup(
    # some basic project information
    name="foobar",
    version=version["__version__"],
    license="GPL3",
    description="Example python project",
    long_description=README,
    author="Evan Widloski",
    author_email="evan_ex@widloski.com",
    url="https://github.com/evidlo/foobar",
    # your project's pip dependencies
    install_requires=[
        "numpy",
        # consider specifying version as well
        "scikit-image==0.17.2",
    ],
    include_package_data=True,
    # automatically look for subfolders with __init__.py
    packages=find_packages(),
    # if you want your code to be able to run directly from command line
    entry_points={
        'console_scripts': [
            'myscript = foobar.foobar:main',
        ]
    },
)
```


foobar/foobar.py

``` python
some_variable = 123

def main():
    print("hello")
```

Install your package using pip (inside the package directory):

    pip install -e .
    
If you install with the "editable" option `-e`, you can make changes to `foobar.py` without reinstalling the package.

We can now run `myscript` from the command line and import things from the package in other Python scripts:

``` bash
[evan@blackbox ~] myscript
hello
```

``` python
>>> from foobar.foobar import some_variable

>>> some_variable
123
```


## Uploading to PyPi

1. Create a pypi account
    
2. Build the package

       python setup.py sdist bdist_wheel
    
3. Upload to pypi with twine

       twine upload/dist*



