#!/bin/bash

# Initial setup
MIX_ENV=prod mix deps.get --only prod
MIX_ENV=prod mix compile

# Compile assets
cd assets && webpack --mode production && cd ..

# Create our digest
MIX_ENV=prod mix phx.digest

# Custom tasks (like DB migrations)
MIX_ENV=prod mix ecto.migrate

# Finally run the server
PORT=80 MIX_ENV=prod mix phx.server