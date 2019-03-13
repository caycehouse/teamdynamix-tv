defmodule TeamdynamixTvWeb.PageController do
  use TeamdynamixTvWeb, :controller

  def index(conn, _params) do
    render(conn, "index.html")
  end
end
