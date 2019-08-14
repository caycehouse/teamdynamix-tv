defmodule TeamdynamixTvWeb.PageControllerTest do
  use TeamdynamixTvWeb.ConnCase

  test "GET /", %{conn: conn} do
    conn = get(conn, "/+Student%20Computer%20Labs")
    assert html_response(conn, 200) =~ "Teamdynamix TV"
  end
end
