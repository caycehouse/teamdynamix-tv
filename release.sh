#!/bin/sh

export SECRET_KEY_BASE=$(mix phx.gen.secret)
export DATABASE_URL=ecto://postgres:postgres@localhost/teamdynamix_tv

# Initial setup
mix deps.get
MIX_ENV=prod mix compile
pushd assets && npm install && popd

# Compile assets
npm run deploy --prefix ./assets
mix phx.digest

# Create release
MIX_ENV=prod mix release