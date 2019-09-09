use Mix.Config

# Configure your endpoint
config :teamdynamix_tv, TeamdynamixTvWeb.Endpoint,
  check_origin: ["//localhost"]

# Configure your database
config :teamdynamix_tv, TeamdynamixTv.Repo,
  username: "",
  password: "",
  database: "",
  hostname: "",
  pool_size: 10

# Configure teamdynamix settings
config :teamdynamix_tv, :teamdynamix_settings,
    auth_url: "",
    new_tickets_url: "",
    ticket_url: "",
    api_ticket_url: "",
    resolutions_url: "",
    username: "",
    password: ""

# Configure papercut settings
config :teamdynamix_tv, :papercut_settings,
    api_token: "",
    printers_url: "",
    devices_url: "",
    system_url: "",
    print_provider_url: "",
    webprint_url: "",
    mobilityprint_url: ""
