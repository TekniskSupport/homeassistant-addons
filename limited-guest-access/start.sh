#!/bin/bash
docker run -d -it -p 8899:8899 -p 8888:8888 -v $(pwd)/data:/data limited-guest-access
