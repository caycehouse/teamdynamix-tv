defmodule TeamdynamixTvWeb.DashboardController do
  use TeamdynamixTvWeb, :controller
  alias Phoenix.LiveView

  def index(conn, %{"resp_group" => resp_group}) do
    LiveView.Controller.live_render(conn, TeamdynamixTvWeb.DashboardView, session: %{
      resp_group: resp_group
    })
  end
end