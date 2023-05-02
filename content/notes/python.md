---
title: Python Packaging Notes
date: 2020
---

Simple guide on creating Python packages.

[TOC]


# Creating a Package

For a package called `foobar`, create the following file structure:

```
my_project/
├── setup.py
├── README.md
└── foobar/
    ├── __init__.py
    ├── version.py
    └── foobar.py
```

**setup.py**

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
    long_description_content_type='text/markdown',
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

**foobar/\__init__.py**

``` python
# Just an Empty file
# This marks the directory as a python module
```

**foobar/version.py**

``` python
# We put the version in its own file so the version can be imported, if necessary
__version__="0.0.1"
```

**foobar/foobar.py**

``` python
some_variable = 123

def main():
    print("hello")
```

**README.md**

```markdown
# Foobar

A description of your project
```

Install your package using pip (inside the package directory):

    pip install -e .
    
If you install with the "editable" option `-e`, you can make changes to `foobar.py` without reinstalling the package.

We can now run `myscript` from the command line: 

``` bash
[evan@blackbox ~] myscript
hello
```

and import things from the package in Python:

``` python
>>> from foobar.foobar import some_variable

>>> some_variable
123
```


# Uploading to PyPi

1. Create a pypi account
    
2. Build the package

       `python setup.py sdist bdist_wheel`
    
3. Upload to pypi with twine

       `twine upload/dist*`


Or use this Makefile:

```
# Evan Widloski - 2019-03-04
# makefile for building Python projects

.PHONY: dist
dist:
	python setup.py sdist bdist_wheel

.PHONY: pypi
pypi: dist
	twine upload dist/*

.PHONY: clean
clean:
	rm dist/*
```

```
make clean
make pypi
```

# Other Good Practices
### Docstrings

All functions should have a docstring that explains what the arguments do and what the function returns.  Here is a simple example of a function with a docstring

``` python
def fibonacci(n):
    """Return Fibonacci sequence up to n elements
    
    Args:
        n (int): number of elements to generate
        
    Returns:
        list: fibonacci sequence of length n
    """
    
    sequence = [0, 1]
    for i in range(n - 2):
        sequence.append(sequence[i - 1] + sequence[i - 2])
        
    return sequence[:n]
```

See more docstring examples [here](https://sphinxcontrib-napoleon.readthedocs.io/en/latest/example_google.html).

### Tests

Tests are an automated way to check that code is working as expected.  They are necessary to ensure your changes to a function don't break code elsewhere that depends on that function.

As an example, all functions in `glide/calibration/foobar.py`, there should a corresponding test function in `glide/calibration/tests/test_foobar.py`.

Here is an example function and its test:

`glide/calibration/foobar.py`

``` python
def fibonacci(n):
    """Return Fibonacci sequence up to n elements
    
    Args:
        n (int): number of elements to generate
        
    Returns:
        list: fibonacci sequence of length n
    """
    
    sequence = [0, 1]
    for i in range(n - 2):
        sequence.append(sequence[i - 1] + sequence[i - 2])
        
    return sequence[:n]
```

`glide/calibration/tests/test_foobar.py`

```python
from glide.calibration.foobar import fibonacci

def test_fibonacci():
    sequence = fibonacci(5)
    assert sequence == [0, 1, 1, 2, 3], "Incorrect fibonacci sequence"
```

Run tests like this from the `glide-sdc/` folder:

``` sh
# run tests for specific module
./run_tests.sh glide/calibration 
# run all tests
./run_tests.sh
```
