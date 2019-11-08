# In this file, we load production configuration and secrets
# from environment variables. You can also hardcode secrets,
# although such is generally not recommended and you have to
# remember to add this file to your .gitignore.
import Config

database_url =
  System.get_env("DATABASE_URL") ||
    raise """
    environment variable DATABASE_URL is missing.
    For example: ecto://USER:PASS@HOST/DATABASE
    """

config :teamdynamix_tv, TeamdynamixTv.Repo,
  # ssl: true,
  url: database_url,
  pool_size: String.to_integer(System.get_env("POOL_SIZE") || "10")

secret_key_base =
  System.get_env("SECRET_KEY_BASE") ||
    raise """
    environment variable SECRET_KEY_BASE is missing.
    You can generate one by calling: mix phx.gen.secret
    """

check_origin =
  System.get_env("CHECK_ORIGIN") ||
    raise """
    environment variable CHECK_ORIGIN is missing.
    """

config :teamdynamix_tv, TeamdynamixTvWeb.Endpoint,
  http: [:inet6, port: String.to_integer(System.get_env("PORT") || "4000")],
  check_origin: [check_origin_url],
  secret_key_base: secret_key_base

# ## Using releases (Elixir v1.9+)
#
# If you are doing OTP releases, you need to instruct Phoenix
# to start each relevant endpoint:
#
config :teamdynamix_tv, TeamdynamixTvWeb.Endpoint, server: true
#
# Then you can assemble a release by calling `mix release`.
# See `mix help release` for more information.

td_auth_url =
  System.get_env("TD_AUTH_URL") ||
    raise """
    environment variable TD_AUTH_URL is missing.
    """

td_new_tickets_url =
  System.get_env("TD_NEW_TICKETS_URL") ||
    raise """
    environment variable TD_NEW_TICKETS_URL is missing.
    """

td_ticket_url =
  System.get_env("TD_TICKET_URL") ||
    raise """
    environment variable TD_TICKET_URL is missing.
    """

td_api_ticket_url =
  System.get_env("TD_API_TICKET_URL") ||
    raise """
    environment variable TD_API_TICKET_URL is missing.
    """

td_resolutions_url =
  System.get_env("TD_RESOLUTIONS_URL") ||
    raise """
    environment variable TD_RESOLUTIONS_URL is missing.
    """

td_username =
  System.get_env("TD_USERNAME") ||
    raise """
    environment variable TD_USERNAME is missing.
    """

td_password =
  System.get_env("TD_PASSWORD") ||
    raise """
    environment variable TD_PASSWORD is missing.
    """

# Configure teamdynamix settings
config :teamdynamix_tv, :teamdynamix_settings,
  auth_url: td_auth_url,
  new_tickets_url: td_new_tickets_url,
  ticket_url: td_ticket_url,
  api_ticket_url: td_api_ticket_url,
  resolutions_url: td_resolutions_url,
  username: td_username,
  password: td_password

pc_api_token =
  System.get_env("PC_API_TOKEN") ||
    raise """
    environment variable PC_API_TOKEN is missing.
    """

pc_printers_url =
  System.get_env("PC_PRINTERS_URL") ||
    raise """
    environment variable PC_PRINTERS_URL is missing.
    """

pc_devices_url =
  System.get_env("PC_DEVICES_URL") ||
    raise """
    environment variable PC_DEVICES_URL is missing.
    """

pc_system_url =
  System.get_env("PC_SYSTEM_URL") ||
    raise """
    environment variable PC_SYSTEM_URL is missing.
    """

pc_print_provider_url =
  System.get_env("PC_PRINT_PROVIDER_URL") ||
    raise """
    environment variable PC_PRINT_PROVIDER_URL is missing.
    """

pc_webprint_url =
  System.get_env("PC_WEBPRINT_URL") ||
    raise """
    environment variable PC_WEBPRINT_URL is missing.
    """

pc_mobilityprint_url =
  System.get_env("PC_MOBILITYPRINT_URL") ||
    raise """
    environment variable PC_MOBILITYPRINT_URL is missing.
    """

# Configure papercut settings
config :teamdynamix_tv, :papercut_settings,
  api_token: pc_api_token,
  printers_url: pc_printers_url,
  devices_url: pc_devices_url,
  system_url: pc_system_url,
  print_provider_url: pc_print_provider_url,
  webprint_url: pc_webprint_url,
  mobilityprint_url: pc_mobilityprint_url
