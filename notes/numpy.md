---
title: Numpy/Pytorch/Matplotlib Notes
author: Evan
date: 2023-12-03
---

# Compute dot product of multidimensional matrix with vector

Supposed we have an (N, N, N, 3) matrix representing the (X, Y, Z) coordinates of all voxels in a rectangular 3D grid.

In order to compute the dot product of each voxel coordinate with a vector, we can do:

    vector = np.array((1, 0, 0))
    np.tensordot(grid, vector, axes=1)


# Update Xarray DataArray Based On Condition

In numpy, you can do this

    >>> xxx[condition] = 123
    
This throws an error on Xarray DataArray

    IndexError: Unlabeled multi-dimensional array cannot be used for indexing: x
    
Instead, do

    >>> xxx = xxx.where(~condition, 123)
    
# Multiple Synced Animations

To sync multiple animation on different subplots:

1. Set all `aniXXX.event_source` to be the same.
2. Call `.save(..., extra_anim=[ani1, ani2, ..., ani(N-1)])` on the last animation

``` python
ax1 = plt.subplot(1, 2, 1)
ani = generate_animation(ax=ax1)

ax2 = plt.subplot(1, 2, 2)
ani2 = generate_other_animation(ax=ax2)
ani2.event_source = ani1.event_source
ani2.save('out.gif', fps=15, extra_anim=[ani1])
```