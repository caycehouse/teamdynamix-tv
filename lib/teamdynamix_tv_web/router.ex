defmodule TeamdynamixTvWeb.Router do
  use TeamdynamixTvWeb, :router

  pipeline :browser do
    plug :accepts, ["html"]
    plug :fetch_session
    plug :fetch_flash
    plug :protect_from_forgery
    plug :put_secure_browser_headers
    plug :fetch_live_flash
  end

  pipeline :api do
    plug :accepts, ["json"]
  end

  scope "/", TeamdynamixTvWeb do
    pipe_through :browser

    get "/:resp_group", DashboardController, :index
  end

  # Other scopes may use custom stacks.
  # scope "/api", TeamdynamixTvWeb do
  #   pipe_through :api
  # end
end
