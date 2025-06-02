---
title: Python Packaging Notes
date: 2025-06-02
---

Simple guide on creating Python packages.

[TOC]


# Creating a Package

For a package called `foobar`, create the following file structure:

```
my_project/
├── pyproject.toml
├── README.md
└── foobar/
    ├── __init__.py
    └── foobar.py
```

**pyproject.toml**


``` toml
[project]
name = "foobar"
version = "0.0.1"
readme = "README.md"
description = "Example python project"
authors = [
    { name = "Evan Widloski", email = "evan_ex@widloski.com" },
]
license = {text = "GPL-3.0"}
keywords = ["your", "keywords", "go", "here"]
requires-python = ">=3.7"
dependencies = [
    "numpy",
    # consider specifying version as well
    "scikit-image==0.17.2",
]
classifiers = [
    "Topic :: Software Development :: Libraries",
    "License :: OSI Approved :: GNU General Public License v3 (GPLv3)",
    "Programming Language :: Python :: 3.7",
    "Programming Language :: Python :: 3.8",
    "Programming Language :: Python :: 3.9",
    "Programming Language :: Python :: 3.10",
    "Programming Language :: Python :: 3.11",
]

[project.urls]
Homepage = "https://github.com/evidlo/foobar"
Repository = "https://github.com/evidlo/foobar"
Issues = "https://github.com/evidlo/foobar/issues"

[project.scripts]
# if you want your code to be able to run directly from command line
myscript = "foobar.foobar:main"

[tool.setuptools]
include-package-data = true

[tool.setuptools.packages]
# automatically look for subfolders with __init__.py
find = {}

[build-system]
requires = ["setuptools>=59.0.0"]
build-backend = 'setuptools.build_meta'
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

## Quickstart

    pip install foobar
    
```

# Installing and Using

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

       `python -m build`
    
3. Upload to pypi with twine

       `twine upload/dist*`


Or use this Makefile:

```
# Evan Widloski - 2019-03-04
# makefile for building Python projects

.PHONY: dist
dist:
    python -m build

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

As an example, for all functions in `foobar/foobar.py`, there should a corresponding test function in `tests/test_foobar.py`.

Here is an example function and its test:

`foobar/foobar.py`

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

`tests/test_foobar.py`

```python
from foobar.foobar import fibonacci

def test_fibonacci():
    sequence = fibonacci(5)
    assert sequence == [0, 1, 1, 2, 3], "Incorrect fibonacci sequence"
```

Run tests like this from the `myproject/` folder:

``` sh
pytest --quiet
```
