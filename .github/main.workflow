workflow "Test and publish release" {
  on = "push"
  resolves = [
    "mix test",
    "github release",
  ]
}

action "mix deps.get" {
  uses = "caycehouse/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "deps.get"
}

action "mix format.check" {
  uses = "caycehouse/actions/elixir/1.9.0@master"
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
  uses = "caycehouse/actions/elixir/1.8.2-postgres@master"
  runs = "mix"
  args = "deps.compile"
  env = {
    MIX_ENV = "test"
  }
  needs = ["mix deps.get"]
}

action "mix test" {
  uses = "caycehouse/actions/elixir/1.8.2-postgres@master"
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
  uses = "caycehouse/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "deps.compile"
  needs = ["branch master"]
  secrets = ["SECRET_KEY_BASE", "DATABASE_URL"]
}

action "mix phx.digest" {
  uses = "caycehouse/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "phx.digest"
  env = {
    MIX_ENV = "prod"
  }
  needs = ["yarn deploy", "mix deps.compile (prod)"]
  secrets = ["SECRET_KEY_BASE", "DATABASE_URL"]
}

action "mix release" {
  uses = "caycehouse/actions/elixir/1.9.0@master"
  runs = "mix"
  args = "release"
  env = {
    MIX_ENV = "prod"
  }
  needs = ["mix phx.digest"]
  secrets = ["SECRET_KEY_BASE", "DATABASE_URL"]
}

action "github release" {
  uses = "caycehouse/actions/bin/ghr@master"
  env = {
    RELEASE_PATH = "_build/prod/rel"
    APPLICATION = "teamdynamix_tv"
  }
  needs = ["mix release", "mix test"]
  secrets = [
    "GITHUB_TOKEN",
  ]
}
