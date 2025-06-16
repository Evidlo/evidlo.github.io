# Document Title

Generate a FUSE daemon in Python which implements the `/path/to/some/dir/@tag1` scheme.  `[tagstring]` is guaranteed not to have any `@` symbol.  Here are some examples of tagstring queries I would like

`foo,bar`: return results which contain both `foo` and `bar` tags

`foo,bar∧`: return results which contain both `foo` and `bar` tags (reverse polish notation)

`foo,bar∨`: return results which contain either `foo` or `bar` tags (reverse polish notation)

`foo,bar∧baz∨`: return results which contain either `foo` and `bar` tags, or `baz` tag (reverse polish notation)

You should be able to create/delete/rename tags by manipulating the tags as if they are folders.  e.g. `rm /path/to/dir/@tag1 -r` would remove  

You should use sqlite as your backing store.



Specific directories are selected to enable tagging.  Tags are globally defined for a system.
These commands assume cwd is a tagging-enabled directory.

- adding tag to existing file
    - `cp foobar.md foobar.md@notes`
- setting tags on a file (overwrite previous tagging)
    - `mv foobar.md foobar.md@notes`
- removing tag from file
    - `rm foobar.md@notes`
- removing a file (also remove file entry from DB)
    - `rm ~/downloads/foobar.md` 
- renaming a tag
    - `mv @foo @bar`
- viewing tags on a file ???
    - `ttt ~/downloads/foobar.md`
