# This file is responsible for configuring your application
# and its dependencies with the aid of the Mix.Config module.
#
# This configuration file is loaded before any dependency and
# is restricted to this project.

# General application configuration
use Mix.Config

config :teamdynamix_tv,
  ecto_repos: [TeamdynamixTv.Repo]

# Configures the endpoint
config :teamdynamix_tv, TeamdynamixTvWeb.Endpoint,
  url: [host: "localhost"],
  secret_key_base: "Y1TSdq2cp9x/aNonYHFSCYwFXrfzvxrrWEkiFTdxXHYeNcrI0zTExLc1gH3YwGL3",
  render_errors: [view: TeamdynamixTvWeb.ErrorView, accepts: ~w(html json)],
  pubsub: [name: TeamdynamixTv.PubSub, adapter: Phoenix.PubSub.PG2],
  live_view: [
    signing_salt: "4mhnoU1C3hYJfv5MAeJmc2WSl/Ma14qt"
  ]

# Configures Elixir's Logger
config :logger, :console,
  format: "$time $metadata[$level] $message\n",
  metadata: [:request_id]

# Use Jason for JSON parsing in Phoenix
config :phoenix, :json_library, Jason

# Enable Phoenix live view
config :phoenix,
  template_engines: [leex: Phoenix.LiveView.Engine]

# Configures quantum
config :teamdynamix_tv, TeamdynamixTv.Scheduler,
  jobs: [
    # Every minute
    {"* * * * *", fn -> Mix.Tasks.Tickets.Get.run([]) end}
  ]

# Import environment specific config. This must remain at the bottom
# of this file so it overrides the configuration defined above.
import_config "#{Mix.env()}.exs"

# Import environment specific secret config. This must remain at the bottom
# of this file so it overrides the configuration defined above.
import_config "#{Mix.env}.secret.exs"