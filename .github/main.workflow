workflow "Test and publish release" {
  on = "push"
  resolves = [
    "mix test",
    "github release",
  ]
}

action "mix deps.get" {
  uses = "moomerman/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "deps.get"
}

action "mix format.check" {
  uses = "moomerman/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "format --check-formatted"
  needs = ["mix deps.get"]
}

action "yarn install" {
  uses = "docker://node:latest"
  needs = ["mix deps.get"]
  runs = "yarn"
  args = "--cwd assets install"
}

action "mix deps.compile (test)" {
  uses = "moomerman/actions/elixir/1.8.2-postgres@master"
  runs = "mix"
  args = "deps.compile"
  env = {
    MIX_ENV = "test"
  }
  needs = ["mix deps.get"]
}

action "mix test" {
  uses = "moomerman/actions/elixir/1.8.2-postgres@master"
  args = "test"
  env = {
    MIX_ENV = "test"
  }
  needs = [
    "mix deps.compile (test)",
    "yarn install",
  ]
}

action "branch master" {
  uses = "actions/bin/filter@master"
  args = "branch master"
  needs = ["mix test"]
}

action "yarn deploy" {
  uses = "docker://node:latest"
  runs = "yarn"
  args = "--cwd assets deploy"
  needs = ["branch master"]
}

action "mix deps.compile (prod)" {
  uses = "moomerman/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "deps.compile"
  env = {
    MIX_ENV = "prod"
    SECRET_KEY_BASE = "ZZXaCt5zThveoP1WYYZZl38Nycr0yjjLrm7dDDbSRxbaDV2rWy1rtwi94xEL6NRN"
    DATABASE_URL = "ecto://postgres:postgres@localhost/teamdynamix_tv"
  }
  needs = ["branch master"]
}

action "mix phx.digest" {
  uses = "moomerman/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "phx.digest"
  env = {
    MIX_ENV = "prod"
  }
  needs = ["yarn deploy", "mix deps.compile (prod)"]
}

action "mix release" {
  uses = "moomerman/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "release"
  env = {
    MIX_ENV = "prod"
  }
  needs = ["mix phx.digest"]
}

action "github release" {
  uses = "moomerman/actions/bin/ghr@master"
  env = {
    RELEASE_PATH = "_build/prod/rel"
    APPLICATION = "TeamdynamixTV"
  }
  needs = ["mix release", "mix test"]
  secrets = [
    "GITHUB_TOKEN",
  ]
}
