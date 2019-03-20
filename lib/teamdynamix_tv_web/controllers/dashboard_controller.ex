defmodule TeamdynamixTvWeb.DashboardController do
  use TeamdynamixTvWeb, :controller
  alias Phoenix.LiveView

  def index(conn, _) do
    LiveView.Controller.live_render(conn, TeamdynamixTvWeb.DashboardView, session: %{})
  end
end